const wordsList = [
  // Levels 1-10 (3 letters)
  'CAT', 'DOG', 'SUN', 'BAT', 'RED', 'CAR', 'BUS', 'BOY', 'TOY', 'PEN',
  // Levels 11-20 (3-4 letters)
  'ANT', 'EGG', 'FOX', 'HAT', 'BIRD', 'FISH', 'TREE', 'MOON', 'BLUE', 'BOOK',
  // Levels 21-30 (4 letters)
  'DESK', 'RAIN', 'SNOW', 'KING', 'STAR', 'DUCK', 'FROG', 'LION', 'BEAR', 'FIRE',
  // Levels 31-40 (5 letters)
  'APPLE', 'TIGER', 'WATER', 'HOUSE', 'GREEN', 'CHAIR', 'TRAIN', 'MOUSE', 'CLOCK', 'GRASS',
  // Levels 41-50 (5 letters)
  'HEART', 'SMILE', 'RIVER', 'HORSE', 'SHEEP', 'BRUSH', 'SHIRT', 'BREAD', 'LIGHT', 'DRINK',
  // Levels 51-60 (6 letters)
  'BANANA', 'ORANGE', 'MONKEY', 'DOCTOR', 'SCHOOL', 'RABBIT', 'PENCIL', 'FLOWER', 'ANIMAL', 'GUITAR',
  // Levels 61-70 (6 letters)
  'CAMERA', 'WINDOW', 'PLANET', 'CASTLE', 'DRAGON', 'PURPLE', 'SILVER', 'SUMMER', 'WINTER', 'COFFEE',
  // Levels 71-80 (7 letters)
  'CHICKEN', 'PENGUIN', 'DOLPHIN', 'TEACHER', 'MORNING', 'DIAMOND', 'WEATHER', 'PICTURE', 'STUDENT', 'BLANKET',
  // Levels 81-90 (8 letters)
  'ELEPHANT', 'COMPUTER', 'MOUNTAIN', 'UMBRELLA', 'HOSPITAL', 'BASEBALL', 'SANDWICH', 'DINOSAUR', 'KANGAROO', 'KEYBOARD',
  // Levels 91-100 (9-10 letters)
  'ALLIGATOR', 'BUTTERFLY', 'CHOCOLATE', 'SUNFLOWER', 'ASTRONAUT', 'WATERMELON', 'HELICOPTER', 'STRAWBERRY', 'MOTORCYCLE', 'TELEVISION'
];

const alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.split('');

function initWordGuess(container, initialLevel, initialScore, saveProgress) {
  let currentLevel = initialLevel;
  let animationId;
  let fallingLetters = [];
  let isGameOver = false;
  
  let targetWord = '';
  let currentLetterIndex = 0;
  
  function render() {
    cancelAnimationFrame(animationId);
    
    if (currentLevel > wordsList.length) {
      container.innerHTML = `<div class="hint-text">Congratulations! You finished all ${wordsList.length} levels.</div>`;
      return;
    }
    
    targetWord = wordsList[currentLevel - 1];
    currentLetterIndex = 0;
    
    container.innerHTML = `
      <div style="display: flex; justify-content: space-between; width: 340px; margin-bottom: 8px;">
        <span style="font-weight: 700; color: #e2e8f0; letter-spacing: 1px; text-transform: uppercase; font-size: 0.9rem;">স্পেলিং চ্যালেঞ্জ</span>
        <span style="font-weight: 800; color: #38bdf8; text-shadow: 0 0 8px rgba(56, 189, 248, 0.5);">Level ${currentLevel}</span>
      </div>
      
      <!-- Premium Play Area -->
      <div id="wg-play-area" style="position: relative; width: 340px; height: 380px; overflow: hidden; background: rgba(30, 41, 59, 0.9); border-radius: 20px; margin-bottom: 10px; border: 1px solid rgba(255,255,255,0.2); box-shadow: 0 15px 35px rgba(0,0,0,0.3), inset 0 0 20px rgba(255,255,255,0.05);">
        <!-- Subtle grid background for modern look -->
        <div style="position:absolute; inset:0; background-image: radial-gradient(rgba(255,255,255,0.05) 1px, transparent 1px); background-size: 20px 20px;"></div>
      </div>
      
      <!-- Word Spelling Slots Area -->
      <div style="background: rgba(30, 41, 59, 0.95); padding: 15px 10px; border-radius: 20px; text-align: center; border: 1px solid rgba(255,255,255,0.2); width: 340px; box-shadow: 0 4px 15px rgba(0,0,0,0.2), inset 0 0 10px rgba(255,255,255,0.05);">
        <div id="wg-word-display" style="display: flex; justify-content: center; gap: 8px; font-size: 1.8rem; font-weight: bold; flex-wrap: wrap;">
        </div>
      </div>
      
      <div id="feedback" style="margin-top: 5px; font-weight: bold; height: 20px; text-align: center; font-size: 1.1rem;"></div>
    `;

    updateWordDisplay();
    startLevel();
  }

  function updateWordDisplay() {
    const displayEl = document.getElementById('wg-word-display');
    if(!displayEl) return;
    
    displayEl.innerHTML = '';
    for(let i=0; i<targetWord.length; i++) {
      let span = document.createElement('span');
      span.innerText = targetWord[i];
      span.style.display = 'inline-flex';
      span.style.alignItems = 'center';
      span.style.justifyContent = 'center';
      // Responsive sizing
      span.style.width = targetWord.length > 7 ? '35px' : '45px';
      span.style.height = targetWord.length > 7 ? '45px' : '55px';
      span.style.fontSize = targetWord.length > 7 ? '1.4rem' : '1.8rem';
      span.style.borderRadius = '10px';
      span.style.transition = 'all 0.3s ease';

      if (i < currentLetterIndex) {
        // Picked correctly - Glowing Green
        span.style.background = 'rgba(16, 185, 129, 0.15)';
        span.style.border = '2px solid #10b981';
        span.style.color = '#10b981';
        span.style.textShadow = '0 0 10px #10b981';
        span.style.boxShadow = '0 0 15px rgba(16, 185, 129, 0.2)';
      } else if (i === currentLetterIndex) {
        // Current letter to pick - Dashed Neon Blue/Yellow
        span.style.background = 'rgba(0,0,0,0.5)';
        span.style.border = '2px dashed #f59e0b';
        span.style.color = 'rgba(245, 158, 11, 0.5)';
        span.style.textShadow = 'none';
      } else {
        // Future letters - Dimmed
        span.style.background = 'rgba(0,0,0,0.3)';
        span.style.border = '2px solid rgba(255,255,255,0.05)';
        span.style.color = 'rgba(255,255,255,0.1)';
      }
      displayEl.appendChild(span);
    }
  }

  function startLevel() {
    isGameOver = false;
    let playArea = document.getElementById('wg-play-area');
    
    // Clean up previous tiles
    Array.from(playArea.children).forEach(child => {
      if(child.tagName === 'DIV' && child.innerText !== '') child.remove();
    });
    fallingLetters = [];
    
    // Spawn initial set of tiles
    for(let i=0; i<6; i++) {
      spawnLetter(i);
    }

    gameLoop();
  }
  
  function spawnLetter(index) {
    if (isGameOver) return;
    let playArea = document.getElementById('wg-play-area');
    if (!playArea) return;
    
    let el = document.createElement('div');
    
    let letterToSpawn;
    if (index % 3 === 0 && currentLetterIndex < targetWord.length) {
      letterToSpawn = targetWord[currentLetterIndex];
    } else {
      letterToSpawn = alphabet[Math.floor(Math.random() * alphabet.length)];
    }
    
    el.innerText = letterToSpawn;
    el.style.position = 'absolute';
    
    // Distribute horizontally into fixed non-overlapping lanes
    // 6 letters max (index 0 to 5). Spaced out evenly.
    let leftPos = 5 + (index * 15); // 5%, 20%, 35%, 50%, 65%, 80%
    el.style.left = leftPos + '%';
    el.style.top = '-60px';
    
    // Sleek Tile Design
    el.style.width = '48px';
    el.style.height = '48px';
    el.style.display = 'flex';
    el.style.alignItems = 'center';
    el.style.justifyContent = 'center';
    el.style.backgroundColor = 'rgba(255,255,255,0.1)';
    el.style.border = '1px solid rgba(255,255,255,0.15)';
    el.style.color = '#ffffff';
    el.style.borderRadius = '12px'; // slightly rounded squares
    el.style.cursor = 'pointer';
    el.style.fontWeight = '800';
    el.style.fontSize = '1.4rem';
    el.style.boxShadow = '0 4px 12px rgba(0,0,0,0.3)';
    el.style.userSelect = 'none';
    el.style.transition = 'transform 0.1s, border-color 0.1s, box-shadow 0.1s';
    
    // Smooth touch interaction
    const handlePick = (e) => {
      e.preventDefault();
      pickLetter(letterToSpawn, el, index);
    };
    el.addEventListener('touchstart', handlePick, {passive: false});
    el.addEventListener('mousedown', handlePick);
    
    el.onmouseover = () => {
      el.style.transform = 'scale(1.15)';
      el.style.borderColor = '#38bdf8';
      el.style.boxShadow = '0 0 15px rgba(56, 189, 248, 0.4)';
    };
    el.onmouseout = () => {
      el.style.transform = 'scale(1)';
      el.style.borderColor = 'rgba(255,255,255,0.15)';
      el.style.boxShadow = '0 4px 12px rgba(0,0,0,0.3)';
    };
    
    playArea.appendChild(el);
    
    let baseSpeed = 1.8 + (currentLevel * 0.06);
    // Stagger vertically so they don't fall in a straight line
    let startY = -(Math.random() * 150 + 50) - (index * 60);
    
    fallingLetters[index] = { el: el, y: startY, speed: baseSpeed + Math.random() * 1.2, letter: letterToSpawn };
  }

  function gameLoop() {
    if (!document.body.contains(container)) return;
    if(isGameOver) return;
    let playArea = document.getElementById('wg-play-area');
    if(!playArea) return; 
    
    fallingLetters.forEach((fl, index) => {
      if(!fl || !fl.el) return;
      
      fl.y += fl.speed;
      fl.el.style.top = fl.y + 'px';
      
      // Hit bottom (Height is 380px now)
      if(fl.y > 380) {
         if (fl.letter === targetWord[currentLetterIndex]) {
             // Restart CURRENT LEVEL if the needed letter is missed
             gameOver("সঠিক অক্ষরটি নিচে পড়ে গেছে! আবার চেষ্টা করুন।");
             return; // Stop processing further letters
         } else {
             fl.el.remove();
             spawnLetter(index);
         }
      }
    });

    animationId = requestAnimationFrame(gameLoop);
  }

  function pickLetter(letter, element, index) {
    if(isGameOver) return;
    
    const neededLetter = targetWord[currentLetterIndex];
    const feedback = document.getElementById('feedback');
    
    if(letter === neededLetter) {
      // Correct pick animation
      element.style.background = 'rgba(16, 185, 129, 0.4)';
      element.style.borderColor = '#10b981';
      element.style.transform = 'scale(1.3)';
      element.style.opacity = '0';
      
      setTimeout(() => {
        if(element.parentNode) element.remove();
        spawnLetter(index); 
      }, 150);
      
      currentLetterIndex++;
      updateWordDisplay();
      
      if (currentLetterIndex >= targetWord.length) {
        // Level complete
        isGameOver = true;
        cancelAnimationFrame(animationId);
        feedback.style.color = '#10b981';
        feedback.innerText = 'অসাধারণ! লেভেল সম্পন্ন।';
        
        setTimeout(() => {
          currentLevel++;
          saveProgress(currentLevel, null);
          render();
        }, 1500);
      }
    } else {
      // Wrong pick - restart CURRENT level
      element.style.background = 'rgba(239, 68, 68, 0.4)';
      element.style.borderColor = '#ef4444';
      gameOver("ভুল অক্ষর! আবার চেষ্টা করুন।");
    }
  }

  function gameOver(msg) {
    isGameOver = true;
    cancelAnimationFrame(animationId);
    const feedback = document.getElementById('feedback');
    feedback.style.color = '#ef4444';
    feedback.innerText = msg;
    
    setTimeout(() => {
      render();
    }, 2000);
  }

  const observer = new MutationObserver((mutations) => {
    if (!document.body.contains(container) || container.innerHTML === '') {
      cancelAnimationFrame(animationId);
      isGameOver = true;
      observer.disconnect();
    }
  });
  observer.observe(container, { childList: true, subtree: true });

  render();
}
