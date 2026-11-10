const {test} = require('node:test');
const assert = require('node:assert/strict');
const {readFileSync} = require('node:fs');
const vm = require('node:vm');
const path = require('node:path');

class Element {
  constructor() { this.events = {}; this.classes = new Set(); this.dataset = {}; this.style = {}; }
  addEventListener(type, fn) { (this.events[type] ||= []).push(fn); }
  emit(type, event = {}) { for (const fn of this.events[type] || []) fn(event); }
  get classList() { return {contains: x => this.classes.has(x), add: x => this.classes.add(x), remove: x => this.classes.delete(x), toggle: (x, on) => on ? this.classes.add(x) : this.classes.delete(x)}; }
  setAttribute(key, value) { this[key] = value; }
  getAttribute(key) { return this[key] || null; }
  contains() { return false; }
  matches() { return false; }
}
class Video extends Element {
  constructor() { super(); this.dataset.src = '/small-preview.mp4'; this.readyState = 0; this.paused = true; this.currentTime = 0; this.frames = new Map(); this.playCalls = 0; }
  play() { this.playCalls++; this.paused = false; return new Promise((resolve, reject) => { this.resolvePlay = resolve; this.rejectPlay = reject; }); }
  pause() { this.paused = true; }
  requestVideoFrameCallback(fn) { const id = this.frames.size + 1; this.frames.set(id, fn); return id; }
  cancelVideoFrameCallback(id) { this.frames.delete(id); }
  decoded() { this.readyState = 3; this.paused = false; this.emit('playing'); this.resolvePlay?.(); }
  frame() { const pending = [...this.frames.values()]; this.frames.clear(); pending.forEach(fn => fn()); }
}
function setup(file, {reduced = false, saveData = false, paired = false} = {}) {
  const video = new Video(), second = new Video(), card = new Element(), document = new Element(), window = new Element(), motion = new Element();
  const observers = [], intervals = []; motion.matches = reduced;
  card['data-project-url'] = '/work/example/'; card.querySelector = () => video;
  const group = new Element(); group.querySelectorAll = () => [video, second];
  document.hidden = false; document.body = new Element();
  document.querySelector = () => null;
  document.querySelectorAll = selector => ({
    '[data-work-card]': [card], '.jb-detail-main video': paired ? [video, second] : [video], '[data-synced-videos]': paired ? [group] : [],
  }[selector] || []);
  window.matchMedia = query => query.includes('reduced-motion') ? motion : {matches: true}; window.innerHeight = 800; window.location = {search: ''};
  window.setInterval = fn => intervals.push(fn);
  class Observer {
    constructor(fn, options = {}) { this.fn = fn; this.options = options; this.targets = []; observers.push(this); }
    observe(target) { this.targets.push(target); }
    fire(target, visible) { this.fn([{target, isIntersecting: visible}]); }
  }
  vm.runInNewContext(readFileSync(path.resolve(__dirname, '../../_public_html/assets/js', file), 'utf8'), {
    window, document, navigator: {connection: {saveData}}, IntersectionObserver: Observer, URLSearchParams,
    Promise, Map, Set, Array, console, location: {},
  });
  return {video, second, card, document, window, motion, observers, intervals, group};
}
const flush = () => new Promise(resolve => setImmediate(resolve));

test('nearby Work cards start fetching before a hover without playing', () => {
  const h = setup('work-page.js');
  h.observers.find(o => o.options.rootMargin).fire(h.card, true);
  assert.equal(h.video.src, '/small-preview.mp4');
  assert.equal(h.video.preload, 'auto');
  assert.equal(h.video.playCalls, 0);
});
test('a cold hover calls play before canplay, then waits for a decoded frame to reveal', async () => {
  const h = setup('work-page.js'); h.card.emit('mouseenter');
  assert.equal(h.video.playCalls, 1); assert.equal(h.video.readyState, 0);
  assert.equal(h.card.classes.has('is-previewing'), false);
  h.video.decoded(); await flush();
  assert.equal(h.card.classes.has('is-previewing'), false);
  h.video.frame(); assert.equal(h.card.classes.has('is-previewing'), true);
});
test('a late playback event after mouseleave cannot reveal or restart the preview', async () => {
  const h = setup('work-page.js'); h.card.emit('mouseenter'); h.card.emit('mouseleave');
  h.video.decoded(); await flush(); h.video.frame();
  assert.equal(h.video.paused, true); assert.equal(h.card.classes.has('is-previewing'), false);
});
test('offscreen cards pause and an interrupted preview can start on a fresh hover', async () => {
  const h = setup('work-page.js'); h.card.emit('mouseenter'); h.video.decoded(); await flush(); h.video.frame();
  h.observers.find(o => !o.options.rootMargin).fire(h.card, false);
  assert.equal(h.video.paused, true); assert.equal(h.card.classes.has('is-previewing'), false);
  h.card.emit('mouseenter'); h.video.decoded(); await flush(); h.video.frame();
  assert.equal(h.card.classes.has('is-previewing'), true);
});
test('data saver avoids speculative requests; reduced motion avoids preview playback', () => {
  const data = setup('work-page.js', {saveData: true});
  data.observers.find(o => o.options.rootMargin).fire(data.card, true);
  assert.equal(data.video.src, undefined);
  data.card.emit('focusin'); assert.equal(data.video.playCalls, 1);
  const motion = setup('work-page.js', {reduced: true});
  motion.observers.find(o => o.options.rootMargin).fire(motion.card, true); motion.card.emit('mouseenter');
  assert.equal(motion.video.src, undefined); assert.equal(motion.video.playCalls, 0);
});
test('a visible detail video requests playback even when metadata has not loaded', async () => {
  const h = setup('project-media.js'); h.observers.find(o => !o.options.rootMargin).fire(h.video, true);
  assert.equal(h.video.readyState, 0); assert.equal(h.video.playCalls, 1);
  h.document.hidden = true; h.document.emit('visibilitychange'); h.video.decoded(); await flush();
  assert.equal(h.video.paused, true);
});
test('paired recordings start together, pause on buffering, and resume on canplay', async () => {
  const h = setup('project-media.js', {paired: true});
  h.observers.find(o => !o.options.rootMargin).fire(h.group, true);
  assert.equal(h.video.playCalls, 1); assert.equal(h.second.playCalls, 1);
  h.video.decoded(); h.second.decoded(); await flush();
  h.second.readyState = 2; h.second.emit('waiting');
  assert.equal(h.video.paused, true); assert.equal(h.second.paused, true);
  h.second.readyState = 3; h.second.emit('canplay');
  assert.equal(h.video.playCalls, 2); assert.equal(h.second.playCalls, 2);
});

test('switching homepage categories cancels stale frame callbacks and permits returning', () => {
  const section = new Element(), links = [new Element(), new Element()], videos = [new Video(), new Video()];
  const document = new Element(), window = new Element(), observers = [];
  const timers = new Map(); let nextTimer = 0;
  section.querySelector = () => new Element();
  section.style.setProperty = () => {};
  section.getBoundingClientRect = () => ({top: 0, bottom: 500});
  document.body = new Element(); document.readyState = 'complete'; document.hidden = false; document.getElementById = () => null;
  document.querySelector = selector => selector === '.jb-actions' ? section : null;
  document.querySelectorAll = selector => selector.startsWith('.jb-action-links') ? links : selector.startsWith('.jb-action-video') ? videos : [];
  links.forEach((link, i) => {link.dataset.videoIndex = String(i); link.matches = () => true;});
  videos.forEach((video, i) => {video.dataset.videoIndex = String(i); video.readyState = 3;});
  window.innerHeight = 800; window.matchMedia = query => ({matches: !query.includes('reduced-motion')});
  const schedule = fn => {timers.set(++nextTimer, fn); return nextTimer;};
  window.setTimeout = schedule;
  class Observer {
    constructor(fn, options = {}) { this.fn = fn; this.options = options; observers.push(this); }
    observe() {} disconnect() {}
    fire(visible) { this.fn([{isIntersecting: visible}]); }
  }
  vm.runInNewContext(readFileSync(path.resolve(__dirname, '../../_public_html/assets/js/piranha-home.js'), 'utf8'), {
    window, document, navigator: {}, history: {}, performance: {getEntriesByType: () => []},
    IntersectionObserver: Observer, MutationObserver: Observer, Map, Set, Array,
    requestAnimationFrame: fn => {fn(); return 1;}, setTimeout: schedule, clearTimeout: id => timers.delete(id),
  });
  observers.find(o => o.options.threshold === 0).fire(true);
  links[0].emit('mouseenter');
  const oldFrame = [...videos[0].frames.values()][0];
  links[0].emit('mouseleave'); links[1].emit('mouseenter');
  assert.equal(videos[0].frames.size, 0);
  links[1].emit('mouseleave'); links[0].emit('mouseenter');
  oldFrame(); assert.equal(section.classes.has('has-preview'), false);
  videos[0].frame(); assert.equal(section.classes.has('has-preview'), true);
  assert.equal(videos[0].classes.has('is-active'), true);
  observers.find(o => o.options.threshold === 0).fire(false);
  assert.equal(section.classes.has('has-preview'), false);
  assert.equal(videos[0].paused, true);
});
