function initColorSort(container, initialLevel, initialScore, saveProgress) {
  let currentLevel = initialLevel;
  
  container.innerHTML = `
    <div style="display: flex; justify-content: space-between; width: 340px; margin-bottom: 15px;">
      <span style="font-weight: 700; color: #e2e8f0; letter-spacing: 1px; text-transform: uppercase; font-size: 0.9rem;">✨ কালার সর্ট ✨</span>
      <span style="font-weight: 800; color: #38bdf8; text-shadow: 0 0 8px rgba(56, 189, 248, 0.5);">Level <span id="cs-level-display">${currentLevel}</span></span>
    </div>

    <!-- Toolbar -->
    <div style="display: flex; justify-content: flex-end; width: 340px; margin-bottom: 20px;">
      <button id="cs-restart-btn" style="background: linear-gradient(135deg, rgba(239, 68, 68, 0.2) 0%, rgba(239, 68, 68, 0.05) 100%); border: 1px solid rgba(239, 68, 68, 0.5); color: #fca5a5; padding: 8px 20px; border-radius: 20px; cursor: pointer; font-weight: bold; font-family: inherit; font-size: 0.95rem; box-shadow: inset 0 2px 5px rgba(255,255,255,0.1), 0 4px 10px rgba(0,0,0,0.2); transition: all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);">
        রিস্টার্ট লেভেল
      </button>
    </div>

    <!-- Play Area -->
    <div id="cs-play-area" style="position: relative; width: 340px; min-height: 400px; padding: 40px 10px 20px 10px; border-radius: 20px; background: rgba(30, 41, 59, 0.9); border: 1px solid rgba(255,255,255,0.2); box-shadow: 0 15px 35px rgba(0,0,0,0.3), inset 0 0 20px rgba(255,255,255,0.05); display: flex; flex-direction: column; align-items: center;">
      <div id="cs-tubes" style="display: flex; gap: 15px; flex-wrap: wrap; justify-content: center; width: 100%;"></div>
    </div>
    
    <div id="cs-msg" style="margin-top: 15px; font-weight: bold; height: 20px; color: #10b981; text-align: center; font-size: 1.1rem; text-shadow: 0 0 10px rgba(16, 185, 129, 0.5);"></div>
  `;

  const tubesEl = document.getElementById('cs-tubes');
  const msgEl = document.getElementById('cs-msg');
  const levelDisplay = document.getElementById('cs-level-display');
  const restartBtn = document.getElementById('cs-restart-btn');

  // Vibrant Solid Colors (Glass shading will provide 3D volume)
  const ALL_COLORS = [
    '#ef4444', // Red
    '#3b82f6', // Blue
    '#10b981', // Green
    '#f59e0b', // Yellow
    '#8b5cf6', // Purple
    '#ec4899', // Pink
    '#14b8a6', // Teal
    '#f97316', // Orange
    '#6366f1', // Indigo
    '#a855f7', // Violet
    '#84cc16', // Lime
    '#0ea5e9'  // Sky
  ];
  
  let tubes = [];
  let selectedTube = -1;
  let numColors = 0;
  let totalTubes = 0;
  let isAnimating = false;

  restartBtn.onmouseover = () => { 
    restartBtn.style.background = 'linear-gradient(135deg, rgba(239, 68, 68, 0.3) 0%, rgba(239, 68, 68, 0.1) 100%)'; 
    restartBtn.style.transform = 'translateY(-2px)';
  };
  restartBtn.onmouseout = () => { 
    restartBtn.style.background = 'linear-gradient(135deg, rgba(239, 68, 68, 0.2) 0%, rgba(239, 68, 68, 0.05) 100%)'; 
    restartBtn.style.transform = 'translateY(0)';
  };
  
  restartBtn.onclick = () => {
    if(isAnimating) return;
    initLevel(false); 
  };

  function initLevel(advanceLevel = true) {
    if (currentLevel > 50) currentLevel = 50;
    levelDisplay.innerText = currentLevel;
    msgEl.innerText = '';
    selectedTube = -1;
    isAnimating = false;
    
    if (currentLevel === 1) numColors = 2;
    else if (currentLevel === 2) numColors = 3;
    else if (currentLevel <= 4) numColors = 4;
    else if (currentLevel <= 10) numColors = 6;
    else if (currentLevel <= 20) numColors = 8;
    else if (currentLevel <= 35) numColors = 10;
    else numColors = 12;
    
    totalTubes = numColors + 2; 
    
    generatePuzzle();
    buildTubesDOM();
    updateTubesDOM();
  }

  function generatePuzzle() {
    let colorsList = [];
    for (let i = 0; i < numColors; i++) {
      for (let j = 0; j < 4; j++) {
        colorsList.push(ALL_COLORS[i]);
      }
    }
    
    for (let i = colorsList.length - 1; i > 0; i--) {
      const j = Math.floor(Math.random() * (i + 1));
      [colorsList[i], colorsList[j]] = [colorsList[j], colorsList[i]];
    }
    
    tubes = [];
    for (let i = 0; i < totalTubes; i++) {
      if (i < numColors) tubes.push(colorsList.splice(0, 4));
      else tubes.push([]);
    }
  }

  function buildTubesDOM() {
    tubesEl.innerHTML = '';
    tubes.forEach((tube, i) => {
      let tubeWidth = totalTubes > 9 ? '40px' : '48px';
      let tubeHeight = totalTubes > 9 ? '150px' : '180px';
      
      let tubeWrap = document.createElement('div');
      tubeWrap.id = `tube-wrap-${i}`;
      tubeWrap.style.width = tubeWidth;
      tubeWrap.style.height = tubeHeight;
      tubeWrap.style.position = 'relative';
      tubeWrap.style.margin = '10px 5px';
      tubeWrap.style.transition = 'transform 0.25s ease-in-out, box-shadow 0.25s';
      tubeWrap.style.transformOrigin = 'top center'; // Crucial to prevent swinging while pouring
      tubeWrap.style.cursor = 'pointer';
      
      // Hyper-realistic Glass Cylinder Overlay
      let glass = document.createElement('div');
      glass.style.position = 'absolute';
      glass.style.inset = '0';
      glass.style.background = 'rgba(255,255,255,0.05)';
      glass.style.borderLeft = '2px solid rgba(255,255,255,0.4)';
      glass.style.borderRight = '2px solid rgba(255,255,255,0.4)';
      glass.style.borderBottom = '2px solid rgba(255,255,255,0.4)';
      glass.style.borderBottomLeftRadius = '25px';
      glass.style.borderBottomRightRadius = '25px';
      glass.style.boxShadow = 'inset -8px 0 15px rgba(0,0,0,0.6), inset 8px 0 15px rgba(255,255,255,0.5), inset 0 -10px 10px rgba(0,0,0,0.4), 0 10px 15px rgba(0,0,0,0.3)';
      glass.style.zIndex = '2';
      glass.style.pointerEvents = 'none';
      
      // Glass Rim
      let rim = document.createElement('div');
      rim.style.position = 'absolute';
      rim.style.top = '-5px';
      rim.style.left = '-2px';
      rim.style.width = 'calc(100% + 4px)';
      rim.style.height = '10px';
      rim.style.border = '2px solid rgba(255,255,255,0.7)';
      rim.style.borderRadius = '50%';
      rim.style.zIndex = '4';
      tubeWrap.appendChild(rim);
      
      // Specular highlight
      let shine = document.createElement('div');
      shine.style.position = 'absolute';
      shine.style.top = '0';
      shine.style.left = '10%';
      shine.style.width = '15%';
      shine.style.height = '95%';
      shine.style.background = 'linear-gradient(90deg, rgba(255,255,255,0.6) 0%, transparent 100%)';
      shine.style.borderRadius = '10px';
      shine.style.zIndex = '3';
      
      // Liquid Container
      let liquidCont = document.createElement('div');
      liquidCont.style.position = 'absolute';
      liquidCont.style.bottom = '4px';
      liquidCont.style.left = '4px';
      liquidCont.style.width = 'calc(100% - 8px)';
      liquidCont.style.height = 'calc(100% - 8px)';
      liquidCont.style.display = 'flex';
      liquidCont.style.flexDirection = 'column-reverse';
      liquidCont.style.borderBottomLeftRadius = '20px';
      liquidCont.style.borderBottomRightRadius = '20px';
      liquidCont.style.overflow = 'hidden'; // Prevents liquid corners from poking out
      liquidCont.style.zIndex = '1';
      
      // 4 Liquid Blocks
      for(let j=0; j<4; j++) {
        let block = document.createElement('div');
        block.id = `tube-${i}-block-${j}`;
        block.style.width = '100%';
        block.style.height = '0%';
        block.style.transition = 'height 0.25s ease-in-out';
        block.style.position = 'relative';
        
        let meniscus = document.createElement('div');
        meniscus.className = 'meniscus';
        meniscus.style.position = 'absolute';
        meniscus.style.top = '-5px';
        meniscus.style.left = '0';
        meniscus.style.width = '100%';
        meniscus.style.height = '10px';
        meniscus.style.borderRadius = '50%';
        meniscus.style.display = 'none';
        meniscus.style.boxShadow = 'inset 0 3px 6px rgba(255,255,255,0.8), inset 0 -2px 4px rgba(0,0,0,0.4)';
        block.appendChild(meniscus);
        
        liquidCont.appendChild(block);
      }
      
      tubeWrap.appendChild(liquidCont);
      tubeWrap.appendChild(glass);
      tubeWrap.appendChild(shine);
      
      tubeWrap.addEventListener('click', () => handleTubeClick(i));
      tubesEl.appendChild(tubeWrap);
    });
  }

  function updateTubesDOM() {
    tubes.forEach((tube, i) => {
      let tubeWrap = document.getElementById(`tube-wrap-${i}`);
      
      if (selectedTube === i) {
        tubeWrap.style.transform = 'translateY(-20px)';
        tubeWrap.style.boxShadow = '0 15px 30px rgba(0,0,0,0.6), 0 0 25px rgba(56, 189, 248, 0.6)';
        tubeWrap.children[1].style.border = '2px solid #38bdf8'; // Highlight glass
        tubeWrap.children[0].style.borderColor = '#38bdf8'; // Highlight rim
      } else {
        tubeWrap.style.boxShadow = 'none';
        tubeWrap.children[1].style.borderLeft = '2px solid rgba(255,255,255,0.4)';
        tubeWrap.children[1].style.borderRight = '2px solid rgba(255,255,255,0.4)';
        tubeWrap.children[1].style.borderBottom = '2px solid rgba(255,255,255,0.4)';
        tubeWrap.children[0].style.borderColor = 'rgba(255,255,255,0.7)';
      }

      for(let j=0; j<4; j++) {
        let blockEl = document.getElementById(`tube-${i}-block-${j}`);
        let meniscus = blockEl.querySelector('.meniscus');
        
        if (j < tube.length) {
          blockEl.style.height = '23%'; // Leaves top 8% empty so meniscus isn't clipped by overflow
          blockEl.style.background = tube[j];
          blockEl.style.boxShadow = 'none';
          
          if (j === 0) {
            blockEl.style.borderBottomLeftRadius = '20px';
            blockEl.style.borderBottomRightRadius = '20px';
          } else {
            blockEl.style.borderRadius = '0';
          }
          
          if (j === tube.length - 1) {
            meniscus.style.display = 'block';
            meniscus.style.background = tube[j];
            meniscus.style.boxShadow = 'inset 0 3px 6px rgba(255,255,255,0.8), inset 0 -2px 4px rgba(0,0,0,0.4)';
          } else {
            meniscus.style.display = 'none';
          }
        } else {
          blockEl.style.height = '0%';
          blockEl.style.boxShadow = 'none';
          meniscus.style.display = 'none';
        }
      }
    });
  }

  function handleTubeClick(i) {
    if(isAnimating) return;
    
    if (selectedTube === -1) {
      if (tubes[i].length > 0) {
        selectedTube = i;
        updateTubesDOM();
      }
    } else {
      if (selectedTube === i) {
        selectedTube = -1; // deselect
        updateTubesDOM();
        return;
      }
      
      let sourceTube = tubes[selectedTube];
      let destTube = tubes[i];
      
      if (sourceTube.length === 0) {
        selectedTube = -1;
        updateTubesDOM();
        return;
      }
      
      let colorToMove = sourceTube[sourceTube.length - 1];
      
      if (destTube.length < 4 && (destTube.length === 0 || destTube[destTube.length - 1] === colorToMove)) {
        isAnimating = true;
        
        let moveCount = 0;
        for(let k = sourceTube.length - 1; k >= 0; k--) {
          if (sourceTube[k] === colorToMove) moveCount++;
          else break;
        }
        
        let spaceAvailable = 4 - destTube.length;
        let actualMoves = Math.min(moveCount, spaceAvailable);
        
        // 1. Move Source Tube & Pour Animation
        let sourceEl = document.getElementById(`tube-wrap-${selectedTube}`);
        let destEl = document.getElementById(`tube-wrap-${i}`);
        
        let rectS = sourceEl.getBoundingClientRect();
        let rectD = destEl.getBoundingClientRect();
        let dx = rectD.left - rectS.left;
        let dy = rectD.top - rectS.top;
        
        let isRight = dx >= 0;
        let rotation = isRight ? 65 : -65;
        let offsetX = 0; // transformOrigin is top center, so lip is perfectly centered
        let offsetY = dy - 20; // Hover slightly above dest lip
        
        sourceEl.style.zIndex = '100';
        sourceEl.style.transform = `translate(${dx + offsetX}px, ${offsetY}px) rotate(${rotation}deg)`;
        
        // 2. Stream
        setTimeout(() => {
          let stream = document.createElement('div');
          stream.style.position = 'absolute';
          stream.style.width = '6px';
          stream.style.height = '0px';
          stream.style.background = colorToMove;
          // Pour directly from top center
          stream.style.left = '50%';
          stream.style.top = '0px';
          stream.style.zIndex = '-1';
          stream.style.transition = 'height 0.1s linear';
          stream.style.transformOrigin = 'top center';
          // Counter-rotate to fall straight down
          stream.style.transform = `translateX(-50%) rotate(${-rotation}deg)`;
          sourceEl.appendChild(stream);
          
          setTimeout(() => {
            stream.style.height = '80px';
            
            // 3. Update logical arrays and DOM liquid levels
            setTimeout(() => {
              for (let m = 0; m < actualMoves; m++) {
                destTube.push(sourceTube.pop());
              }
              let prevSelected = selectedTube;
              selectedTube = -1;
              updateTubesDOM(); // Transitions liquid height
              
              // 4. Clean up stream and return tube
              stream.style.opacity = '0';
              setTimeout(() => stream.remove(), 100);
              
              sourceEl.style.transform = 'translate(0, 0) rotate(0deg)';
              sourceEl.style.zIndex = '1';
              
              setTimeout(() => {
                isAnimating = false;
                checkWin();
              }, 250);
            }, 100);
          }, 50);
        }, 200);

      } else {
        // Invalid move shake animation
        selectedTube = -1;
        updateTubesDOM();
        let wrap = document.getElementById(`tube-wrap-${i}`);
        wrap.animate([
          { transform: 'translateX(-5px)' },
          { transform: 'translateX(5px)' },
          { transform: 'translateX(-5px)' },
          { transform: 'translateX(5px)' },
          { transform: 'translateX(0)' }
        ], { duration: 300 });
      }
    }
  }

  function checkWin() {
    let win = true;
    for (let i = 0; i < totalTubes; i++) {
      if (tubes[i].length > 0) {
        if (tubes[i].length < 4) {
          win = false;
          break;
        }
        let firstColor = tubes[i][0];
        if (!tubes[i].every(c => c === firstColor)) {
          win = false;
          break;
        }
      }
    }
    
    if (win) {
      msgEl.innerText = 'অসাধারণ! আপনি লেভেলটি সমাধান করেছেন!';
      isAnimating = true;
      setTimeout(() => {
        currentLevel++;
        saveProgress(currentLevel, null);
        initLevel(true);
      }, 2000);
    }
  }

  initLevel(false);
}
