// Simple keyboard-driven menu for Mario Kart SNES-style screen
document.addEventListener('DOMContentLoaded', () => {
	const menu = document.querySelector('.menu');
	const items = Array.from(document.querySelectorAll('.menu-item'));
	let index = 0;

	// START SCREEN handling
	const stage = document.querySelector('.stage');
	const startScreen = document.querySelector('.start-screen');
	let started = false;

	// Adjusted setTimeout duration in startGame
	function startGame(){
		if(!stage.classList.contains('start')) return;
		started = true;
		// fade out the start screen then remove the start class so menu shows
		startScreen.classList.add('fade-out');
		startScreen.addEventListener('transitionend', ()=>{
			stage.classList.remove('start');
			startScreen.classList.remove('fade-out');
		}, {once:true});
		// fallback in case transitionend doesn't fire
		setTimeout(()=>{
			if(stage.classList.contains('start')) {
				stage.classList.remove('start');
				console.log('Fallback triggered: Removed start class');
			}
		}, 100); // Increased duration to ensure proper timing
	}

	// click or press B to start
	startScreen && startScreen.addEventListener('click', startGame);

	// Fit the 255x224 stage into the viewport while preserving aspect ratio.
	const crt = document.querySelector('.crt');
	function fitStage(){
		const targetW = 255;
		const targetH = 224;
		const margin = 0.94; // leave a little room
		const vw = window.innerWidth * margin;
		const vh = window.innerHeight * margin;
		const scale = Math.min(vw / targetW, vh / targetH);
		crt.style.transform = `scale(${scale})`;
		crt.style.transformOrigin = 'center center';
	}
	fitStage();
	window.addEventListener('resize', fitStage);

	// Adjusted scrollIntoView behavior in setActive
	function setActive(i){
		items.forEach((it, idx) => {
			it.classList.toggle('active', idx === i);
		});
		// ensure focused for keyboard scroll
		const active = items[i];
		active && active.scrollIntoView({block:'center', behavior:'smooth'}); // Changed block to 'center'
	}

	setActive(index);

	// mouse interaction
	items.forEach((it, i) => {
		it.addEventListener('mouseenter', () => { index = i; setActive(index); });
		it.addEventListener('click', () => activate(index));
	});

	// Remove inline styles from back arrow button
	function addBackButton(menuElement, onClickHandler) {
		const backButton = document.createElement('div');
		backButton.className = 'back-button';
		backButton.textContent = '←';
		backButton.addEventListener('click', onClickHandler);
		menuElement.appendChild(backButton);
	}

	// Updated showSelectCupMenu to replace the Select Mode menu
	function showSelectCupMenu() {
		const selectCupMenu = document.createElement('div');
		selectCupMenu.className = 'select-cup-menu';

		const cups = ['Mushroom Cup', 'Flower Cup', 'Star Cup', 'Special Cup'];
		cups.forEach(cup => {
			const cupItem = document.createElement('div');
			cupItem.textContent = cup;
			cupItem.className = 'menu-item';
			cupItem.addEventListener('mouseenter', () => {
				cupItem.classList.add('active');
			});
			cupItem.addEventListener('mouseleave', () => {
				cupItem.classList.remove('active');
			});
			cupItem.addEventListener('click', () => {
				showSelection(`${cup} selected`);
				stage.removeChild(selectCupMenu);
			});
			selectCupMenu.appendChild(cupItem);
		});

		const stage = document.querySelector('.stage');
		const currentMenu = document.querySelector('.menu-window');
		if (currentMenu) {
			currentMenu.remove();
		}

		addBackButton(selectCupMenu, () => {
			stage.removeChild(selectCupMenu);
			stage.appendChild(currentMenu);
		});

		stage.appendChild(selectCupMenu);
	}

	// Ensure back button is added to Select Mode menu
	function showSelectModeMenu() {
		const selectModeMenu = document.querySelector('.menu-window');
		addBackButton(selectModeMenu, () => {
			console.log('Back button clicked on Select Mode menu');
		});
	}

	// Modify the activate function to handle 'Start Race'
	function activate(i) {
		const action = items[i].dataset.action;

		if (action === 'exit') {
			stage.classList.add('start');
			startScreen.classList.remove('fade-out');
			started = false;
			showSelection('Returning to title...');
			return;
		}

		if (action === 'start-race') {
			showSelectCupMenu();
			return;
		}

		// simple behavior: show a modal or log; replace with real routing later
		// We'll show a short overlay to simulate selection
		showSelection(items[i].textContent + ' selected');
		console.log('Selected action:', action);
	}

	function showSelection(text){
		let overlay = document.querySelector('.selection-overlay');
		if(!overlay){
			overlay = document.createElement('div');
			overlay.className = 'selection-overlay';
			Object.assign(overlay.style, {
				position:'absolute', left:0, right:0, top:0, bottom:0,
				display:'flex',alignItems:'center',justifyContent:'center',
				background:'rgba(2,6,23,0.6)', color:'#fff', fontSize:'22px',
				zIndex:40, backdropFilter:'blur(2px)'
			});
			document.querySelector('.stage').appendChild(overlay);
		}
		overlay.textContent = text;
		overlay.style.opacity = '0';
		overlay.animate([{opacity:0},{opacity:1}], {duration:180, fill:'forwards'});
		setTimeout(()=>{
			overlay.animate([{opacity:1},{opacity:0}], {duration:220, fill:'forwards'});
		}, 700);
	}

	// keyboard navigation
	window.addEventListener('keydown', (e) => {
		// If not started, allow 'b' to start
		if(!started && (e.key === 'b' || e.key === 'B')){
			e.preventDefault();
			startGame();
			return;
		}
		if(['ArrowUp','k'].includes(e.key)){
			e.preventDefault();
			index = (index - 1 + items.length) % items.length;
			setActive(index);
		} else if(['ArrowDown','j'].includes(e.key)){
			e.preventDefault();
			index = (index + 1) % items.length;
			setActive(index);
		} else if(e.key === 'Enter'){
			e.preventDefault();
			activate(index);
		} else if(e.key === 'ArrowLeft' || e.key === 'ArrowRight'){
			// placeholder for cup switching
			const dir = e.key === 'ArrowLeft' ? 'Left' : 'Right';
			showSelection('Cup changed ' + dir);
		}
	});

	// Accessibility: let the menu receive focus so keyboard works after click
	menu.addEventListener('focus', ()=> setActive(index));
});
