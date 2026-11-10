const ns = 'http://www.w3.org/2000/svg';
let editorModule;
function loadEditor() {
  if (!editorModule) {
    const css = document.createElement('link');
    css.rel = 'stylesheet'; css.href = '/assets/lib/source-editor/editor.css'; document.head.append(css);
    editorModule = import('/assets/lib/source-editor/editor.js?v=20261110-gdscript');
  }
  return editorModule;
}
function svgElement(name, attributes = {}, text) {
  const el = document.createElementNS(ns, name);
  Object.entries(attributes).forEach(([key,value]) => el.setAttribute(key, String(value)));
  if (text !== undefined) el.textContent = text;
  return el;
}
const pinColor = type => ({exec:'#eee',bool:'#b84040',real:'#7bcc8a',float:'#7bcc8a',int:'#49cbbd',object:'#50a7dc',struct:'#e9c673',string:'#ed77bd',name:'#b181db'}[type] || '#aaa');
function mountBlueprint(stage, item) {
  const graph = item.data;
  const svg = svgElement('svg', {class:'jb-blueprint-viewport', tabindex:0, role:'img', 'aria-label':item.file + ', ' + graph.name + '. Drag to pan; plus and minus to zoom; zero to fit.'});
  const wires = svgElement('g'), nodesLayer = svgElement('g'); svg.append(wires,nodesLayer);
  const pinPositions = new Map();
  // Keep the saved arrangement, with room for web labels and touch targets.
  const nodes = graph.nodes.filter(node => !node.class.endsWith('EdGraphNode_Comment')).map(node=>({...node,x:node.x*1.6,y:node.y*1.6}));
  const bounds = {left:Infinity,top:Infinity,right:-Infinity,bottom:-Infinity};
  nodes.forEach(node => {
    const inputs = node.pins.filter(pin => pin.direction === 0 && !pin.hidden && (pin.links.length || pin.default || pin.type === 'exec'));
    const outputs = node.pins.filter(pin => pin.direction === 1 && !pin.hidden && (pin.links.length || pin.type === 'exec'));
    const titleLines = node.title.split('\n');
    const title = titleLines[0];
    const pieces = title.match(/.{1,24}(?:\s|$)|.{1,24}/g) || [title];
    const compact = node.class.includes('VariableGet');
    const reroute = node.class.includes('K2Node_Knot');
    const head = compact || reroute ? 0 : Math.max(42,pieces.length * 20 + 16);
    const width = reroute ? 16 : compact ? Math.max(120,Math.min(210,title.length*7+35)) : 220, height = reroute ? 16 : compact ? 38 : head + Math.max(inputs.length, outputs.length,1) * 27 + 12;
    bounds.left=Math.min(bounds.left,node.x);bounds.top=Math.min(bounds.top,node.y);bounds.right=Math.max(bounds.right,node.x+width);bounds.bottom=Math.max(bounds.bottom,node.y+height);
    const group = svgElement('g',{class:'jb-graph-node',transform:'translate('+node.x+' '+node.y+')','data-node-guid':node.guid});
    group.append(svgElement('title',{},node.title));
    group.append(svgElement('rect',{width,height,rx:7,fill:'#161616'}));
    let color = '#244c68';
    if (node.class.includes('CustomEvent') || node.class.endsWith('K2Node_Event')) color='#842d2d';
    else if (node.class.includes('FunctionEntry')) color='#593a71';
    else if (node.class.includes('Variable')) color='#354c36';
    else if (node.class.includes('IfThenElse')) color='#575757';
    if (!compact && !reroute) group.append(svgElement('path',{d:'M7 0H'+(width-7)+'Q'+width+' 0 '+width+' 7V'+head+'H0V7Q0 0 7 0Z',fill:color}));
    if(reroute) {}
    else if(compact) group.append(svgElement('text',{x:12,y:24,class:'jb-graph-node-title'},title));
    else pieces.forEach((line,i) => group.append(svgElement('text',{x:12,y:24+i*20,class:'jb-graph-node-title'},line.trim())));
    function pinRow(pin,index,output) {
      const x=output?width:0, y=reroute ? 8 : head+20+index*27;
      pinPositions.set(node.guid+':'+pin.name,{x:node.x+x,y:node.y+y,type:pin.type});
      if (pin.type==='exec') group.append(svgElement('path',{d:output?'M'+(width-6)+' '+(y-6)+'L'+(width+2)+' '+y+'L'+(width-6)+' '+(y+6)+'Z':'M-3 '+(y-6)+'H4L10 '+y+'L4 '+(y+6)+'H-3Z',fill:'#eee'}));
      else group.append(svgElement('circle',{cx:x,cy:y,r:5,fill:pin.links.length?pinColor(pin.type):'#161616',stroke:pinColor(pin.type),'stroke-width':2}));
      let label=pin.name.replace(/([a-z])([A-Z])/g,'$1 $2');
      if (pin.type==='exec') label=({execute:'',then:'',else:'False'}[pin.name] ?? label);
      if (!output && !pin.links.length && pin.default) label+=' = '+pin.default;
      if(node.class.includes('IfThenElse')&&pin.name==='then')label='True';
      if (compact || reroute) label='';
      if (label.length>24) label=label.slice(0,22)+'…';
      group.append(svgElement('text',{x:output?width-12:14,y:y+5,'text-anchor':output?'end':'start'},label));
    }
    inputs.forEach((pin,i)=>pinRow(pin,i,false));outputs.forEach((pin,i)=>pinRow(pin,i,true));
    nodesLayer.append(group);
  });
  let wireCount=0;
  nodes.forEach(node => node.pins.filter(pin=>pin.direction===1).forEach(pin=> {
    const a=pinPositions.get(node.guid+':'+pin.name);if(!a)return;
    pin.links.forEach(link=> {const b=pinPositions.get(link);if(!b)return;const dx=Math.max(65,Math.abs(b.x-a.x)*0.45);
      wires.append(svgElement('path',{class:'jb-graph-wire',d:'M'+a.x+' '+a.y+'C'+(a.x+dx)+' '+a.y+' '+(b.x-dx)+' '+b.y+' '+b.x+' '+b.y,stroke:pinColor(a.type)}));wireCount++;
    });
  }));
  svg.dataset.nodeCount=nodes.length;svg.dataset.wireCount=wireCount;
  const controls=document.createElement('div');controls.className='jb-graph-controls';
  const focusEntry=Boolean(item.focus_node)||(matchMedia('(max-width: 760px)').matches&&nodes.length>8);
  let fitted, box;
  function apply() { svg.setAttribute('viewBox',[box.x,box.y,box.w,box.h].join(' ')); }
  function fit() {
    const rect=stage.getBoundingClientRect(),aspect=rect.width/(rect.height||480);
    let w=bounds.right-bounds.left+140,h=bounds.bottom-bounds.top+140;
    if(w/h>aspect) h=w/aspect;else w=h*aspect;
    fitted={x:(bounds.left+bounds.right-w)/2,y:(bounds.top+bounds.bottom-h)/2,w,h};box={...fitted};apply();
  }
  function zoom(factor) {
    const w=box.w*factor;if(w<200||w>fitted.w*1.5)return;
    box={x:box.x+(box.w-w)/2,y:box.y+(box.h-box.h*factor)/2,w,h:box.h*factor};apply();
  }
  function start() { const node=nodes.find(node=>node.guid===item.focus_node)||nodes.find(node=>node.class.includes('FunctionEntry'))||nodes[0];const rect=stage.getBoundingClientRect();const w=Math.min(fitted.w,Math.max(600,rect.width*1.4));box={x:node.x-100,y:node.y-100,w,h:w*rect.height/rect.width};apply(); }
  [['−','Zoom out',()=>zoom(1.25)],['+','Zoom in',()=>zoom(0.8)],...(focusEntry ? [['Start','Start of graph',start]] : []),['Fit all','Fit entire graph',fit]].forEach(([text,label,action])=>{const button=document.createElement('button');button.type='button';button.textContent=text;button.setAttribute('aria-label',label);button.addEventListener('click',action);controls.append(button)});
  let drag;
  svg.addEventListener('pointerdown',event=>{if(event.button!==0)return;drag={x:event.clientX,y:event.clientY,box:{...box},touch:event.pointerType==='touch'};svg.setPointerCapture(event.pointerId)});
  svg.addEventListener('pointermove',event=>{if(!drag)return;const dx=event.clientX-drag.x,dy=event.clientY-drag.y;if(drag.touch&&Math.abs(dy)>Math.abs(dx))return;const rect=svg.getBoundingClientRect();box={...drag.box,x:drag.box.x-dx*drag.box.w/rect.width,y:drag.box.y-dy*drag.box.h/rect.height};apply()});
  ['pointerup','pointercancel','lostpointercapture'].forEach(name=>svg.addEventListener(name,()=>{drag=null}));
  svg.addEventListener('keydown',event=>{if(event.key==='+'||event.key==='=')zoom(0.8);else if(event.key==='-')zoom(1.25);else if(event.key==='0')fit();else if(['ArrowLeft','ArrowRight','ArrowUp','ArrowDown'].includes(event.key)){box.x+=(event.key==='ArrowLeft'?-1:event.key==='ArrowRight'?1:0)*box.w*0.1;box.y+=(event.key==='ArrowUp'?-1:event.key==='ArrowDown'?1:0)*box.h*0.1;apply()}else return;event.preventDefault()});
  stage.replaceChildren(svg,controls);fit();if(focusEntry)start();
  function layout() {
    const rect=stage.getBoundingClientRect();
    if(!box || !rect.width || !rect.height)return;
    const centerY=box.y+box.h/2;
    box.h=box.w*rect.height/rect.width;box.y=centerY-box.h/2;apply();
  }
  return {layout};
}
function initWorkbench(root) {
  const items=JSON.parse(root.querySelector('[data-source-payload]').textContent);
  const tabs=[...root.querySelectorAll('[data-source-tab]')];
  const panels=[...root.querySelectorAll('[data-source-panel]')];
  const mounted=new Map();let current=0,visible=false;
  async function mount(index) {
    const panel=panels[index],stage=panel.querySelector('[data-source-stage]'),item=items[index];
    if(mounted.has(index)){mounted.get(index)?.layout();return}
    mounted.set(index,null);
    if(item.kind==='blueprint'){mounted.set(index,mountBlueprint(stage,item));return}
    // Mobile keeps the selectable, server-rendered source and native scrolling.
    if(matchMedia('(max-width: 760px)').matches)return;
    try {const module=await loadEditor();const host=document.createElement('div');host.className='jb-source-editor';stage.append(host);const editor=module.createSourceEditor(host,item);stage.querySelector('.jb-source-fallback').hidden=true;mounted.set(index,editor);if(index===current)editor.layout();}
    catch(error){mounted.delete(index);console.warn('Source editor unavailable; keeping the source text.',error)}
  }
  function select(index,focus=false) {
    current=index;
    tabs.forEach((tab,i)=>{tab.setAttribute('aria-selected',String(i===index));tab.tabIndex=i===index?0:-1;panels[i].hidden=i!==index});
    const summary=root.querySelector('[data-source-summary]');if(summary)summary.textContent=items[index].summary;
    const link=root.querySelector('[data-source-project]');if(link){link.href=items[index].url;link.textContent=items[index].project}
    if(visible)mount(index);if(focus)tabs[index].focus();
  }
  tabs.forEach((tab,index)=>{tab.addEventListener('click',()=>select(index));tab.addEventListener('keydown',event=>{let next;if(event.key==='ArrowRight')next=(index+1)%tabs.length;else if(event.key==='ArrowLeft')next=(index+tabs.length-1)%tabs.length;else if(event.key==='Home')next=0;else if(event.key==='End')next=tabs.length-1;else return;event.preventDefault();select(next,true)})});
  let dialog=null,placeholder=null,returnFocus=null;
  function expand(button) {
    if(dialog)return;
    returnFocus=button;placeholder=document.createComment('source viewer');root.before(placeholder);
    dialog=document.createElement('dialog');dialog.className='jb-source-dialog';dialog.setAttribute('aria-label','Expanded project source');dialog.setAttribute('data-lenis-prevent','');document.body.append(dialog);dialog.append(root);
    root.querySelectorAll('[data-source-expand]').forEach(button=>button.hidden=true);
    root.querySelectorAll('[data-source-close]').forEach(button=>button.hidden=false);
    dialog.addEventListener('close',()=>{placeholder.replaceWith(root);root.querySelectorAll('[data-source-expand]').forEach(button=>button.hidden=false);root.querySelectorAll('[data-source-close]').forEach(button=>button.hidden=true);dialog.remove();dialog=null;const focusTarget=panels[current].querySelector('[data-source-expand]')||returnFocus;focusTarget.focus({preventScroll:true});mounted.get(current)?.layout();},{once:true});
    dialog.showModal();mounted.get(current)?.layout();
  }
  root.querySelectorAll('[data-source-expand]').forEach(button=>button.addEventListener('click',()=>expand(button)));
  root.querySelectorAll('[data-source-close]').forEach(button=>button.addEventListener('click',()=>dialog?.close()));
  panels.forEach((panel,index)=>panel.querySelector('[data-source-copy]').addEventListener('click',async()=>{const feedback=panel.querySelector('[data-source-feedback]');try{const item=items[index];await navigator.clipboard.writeText(item.kind==='code'?item.code:JSON.stringify(item.data,null,2));feedback.textContent='Copied';setTimeout(()=>feedback.textContent='',1800)}catch(error){feedback.textContent='Use Download to get the source.'}}));
  new IntersectionObserver(entries=>{visible=entries[0].isIntersecting;if(visible)mount(current)},{rootMargin:'200px'}).observe(root);
  new ResizeObserver(()=>{mounted.get(current)?.layout()}).observe(root);
}
document.querySelectorAll('[data-source-viewer]').forEach(initWorkbench);

// Chapter anchors follow native page scrolling.
const chapters=[...document.querySelectorAll('.jb-case-chapter[id]')];
const chapterLinks=[...document.querySelectorAll('.jb-case-nav a')];
if(chapters.length&&chapterLinks.length){
  const nav=document.querySelector('.jb-case-nav');
  let queued=false;
  function updateChapter(){
    queued=false;
    const readingLine=(parseFloat(getComputedStyle(nav).top)||0)+nav.offsetHeight+24;
    let active=chapters[0];
    for(const chapter of chapters){if(chapter.getBoundingClientRect().top<=readingLine)active=chapter;else break;}
    chapterLinks.forEach(link=>{if(link.hash==='#'+active.id)link.setAttribute('aria-current','location');else link.removeAttribute('aria-current')});
  }
  function queueUpdate(){if(!queued){queued=true;requestAnimationFrame(updateChapter);}}
  addEventListener('scroll',queueUpdate,{passive:true});
  addEventListener('resize',queueUpdate);
  updateChapter();
}
