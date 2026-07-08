const rebusData = [
  { eq: [{e:'🔥', t:'Hot'}, '+', {e:'🐶', t:'Dog'}], ans: 'Hotdog', ansIcon: '🌭' },
  { eq: [{e:'☀️', t:'Sun'}, '+', {e:'🌻', t:'Flower'}], ans: 'Sunflower', ansIcon: '🌻' },
  { eq: [{e:'🌧️', t:'Rain'}, '+', {e:'🏹', t:'Bow'}], ans: 'Rainbow', ansIcon: '🌈' },
  { eq: [{e:'⭐', t:'Star'}, '+', {e:'🐟', t:'Fish'}], ans: 'Starfish', ansIcon: '🐠' },
  { eq: [{e:'❄️', t:'Snow'}, '+', {e:'👨', t:'Man'}], ans: 'Snowman', ansIcon: '⛄' },
  { eq: [{e:'🔥', t:'Fire'}, '+', {e:'🪰', t:'Fly'}], ans: 'Firefly', ansIcon: '✨' },
  { eq: [{e:'🍯', t:'Honey'}, '+', {e:'🐝', t:'Bee'}], ans: 'Honeybee', ansIcon: '🐝' },
  { eq: [{e:'🥤', t:'Straw'}, '+', {e:'🍒', t:'Berry'}], ans: 'Strawberry', ansIcon: '🍓' },
  { eq: [{e:'🌊', t:'Sea'}, '+', {e:'🐎', t:'Horse'}], ans: 'Seahorse', ansIcon: '🐉' },
  { eq: [{e:'🍳', t:'Pan'}, '+', {e:'🍰', t:'Cake'}], ans: 'Pancake', ansIcon: '🥞' },
  { eq: [{e:'🚪', t:'Door'}, '+', {e:'🔔', t:'Bell'}], ans: 'Doorbell', ansIcon: '🛎️' },
  { eq: [{e:'🧺', t:'Basket'}, '+', {e:'⚽', t:'Ball'}], ans: 'Basketball', ansIcon: '🏀' },
  { eq: [{e:'💧', t:'Water'}, '+', {e:'🍈', t:'Melon'}], ans: 'Watermelon', ansIcon: '🍉' },
  { eq: [{e:'👁️', t:'Eye'}, '+', {e:'🥃', t:'Glass'}], ans: 'Eyeglass', ansIcon: '👓' },
  { eq: [{e:'🦶', t:'Foot'}, '+', {e:'⚽', t:'Ball'}], ans: 'Football', ansIcon: '⚽' },
  { eq: [{e:'🧈', t:'Butter'}, '+', {e:'🪰', t:'Fly'}], ans: 'Butterfly', ansIcon: '🦋' },
  { eq: [{e:'👂', t:'Ear'}, '+', {e:'💍', t:'Ring'}], ans: 'Earring', ansIcon: '💎' },
  { eq: [{e:'🐮', t:'Cow'}, '+', {e:'👦', t:'Boy'}], ans: 'Cowboy', ansIcon: '🤠' },
  { eq: [{e:'🌙', t:'Night'}, '+', {e:'👗', t:'Gown'}], ans: 'Nightgown', ansIcon: '🥻' },
  { eq: [{e:'☀️', t:'Sun'}, '+', {e:'👓', t:'Glasses'}], ans: 'Sunglasses', ansIcon: '🕶️' },
  { eq: [{e:'🦷', t:'Tooth'}, '+', {e:'🖌️', t:'Brush'}], ans: 'Toothbrush', ansIcon: '🪥' },
  { eq: [{e:'🌲', t:'Pine'}, '+', {e:'🍎', t:'Apple'}], ans: 'Pineapple', ansIcon: '🍍' },
  { eq: [{e:'🔑', t:'Key'}, '+', {e:'🛹', t:'Board'}], ans: 'Keyboard', ansIcon: '⌨️' },
  { eq: [{e:'🎈', t:'Pop'}, '+', {e:'🌽', t:'Corn'}], ans: 'Popcorn', ansIcon: '🍿' },
  { eq: [{e:'👄', t:'Lip'}, '+', {e:'🦯', t:'Stick'}], ans: 'Lipstick', ansIcon: '💄' },
  { eq: [{e:'💪', t:'Arm'}, '+', {e:'🪑', t:'Chair'}], ans: 'Armchair', ansIcon: '🪑' },
  { eq: [{e:'🌃', t:'Night'}, '+', {e:'🦉', t:'Owl'}], ans: 'Nightowl', ansIcon: '🦉' },
  { eq: [{e:'❄️', t:'Snow'}, '+', {e:'⚾', t:'Ball'}], ans: 'Snowball', ansIcon: '🌨️' },
  { eq: [{e:'⬛', t:'Black'}, '+', {e:'🛹', t:'Board'}], ans: 'Blackboard', ansIcon: '⬛' },
  { eq: [{e:'🛞', t:'Wheel'}, '+', {e:'🪑', t:'Chair'}], ans: 'Wheelchair', ansIcon: '🦽' },
  
  // Levels 31-100 Added
  { eq: [{e:'❄️', t:'Snow'}, '+', {e:'💧', t:'Drop'}], ans: 'Snowdrop', ansIcon: '🥀' },
  { eq: [{e:'🔥', t:'Fire'}, '+', {e:'🪵', t:'Wood'}], ans: 'Firewood', ansIcon: '🪵' },
  { eq: [{e:'🍎', t:'Apple'}, '+', {e:'🥧', t:'Pie'}], ans: 'Applepie', ansIcon: '🥧' },
  { eq: [{e:'🔵', t:'Blue'}, '+', {e:'🫐', t:'Berry'}], ans: 'Blueberry', ansIcon: '🫐' },
  { eq: [{e:'⚫', t:'Black'}, '+', {e:'🫐', t:'Berry'}], ans: 'Blackberry', ansIcon: '📱' },
  { eq: [{e:'🥚', t:'Egg'}, '+', {e:'🌿', t:'Plant'}], ans: 'Eggplant', ansIcon: '🍆' },
  { eq: [{e:'🌊', t:'Sea'}, '+', {e:'🐚', t:'Shell'}], ans: 'Seashell', ansIcon: '🐚' },
  { eq: [{e:'☀️', t:'Sun'}, '+', {e:'💡', t:'Light'}], ans: 'Sunlight', ansIcon: '🌤️' },
  { eq: [{e:'🌙', t:'Moon'}, '+', {e:'💡', t:'Light'}], ans: 'Moonlight', ansIcon: '🌕' },
  { eq: [{e:'🌲', t:'Pine'}, '+', {e:'🌰', t:'Cone'}], ans: 'Pinecone', ansIcon: '🌲' },
  { eq: [{e:'🍉', t:'Water'}, '+', {e:'🛝', t:'Slide'}], ans: 'Waterslide', ansIcon: '🛝' },
  { eq: [{e:'🧈', t:'Butter'}, '+', {e:'🥛', t:'Milk'}], ans: 'Buttermilk', ansIcon: '🥛' },
  { eq: [{e:'🥜', t:'Pea'}, '+', {e:'🌰', t:'Nut'}], ans: 'Peanut', ansIcon: '🥜' },
  { eq: [{e:'🫖', t:'Tea'}, '+', {e:'🥄', t:'Spoon'}], ans: 'Teaspoon', ansIcon: '🥄' },
  { eq: [{e:'☕', t:'Coffee'}, '+', {e:'🥤', t:'Cup'}], ans: 'Coffeecup', ansIcon: '☕' },
  { eq: [{e:'🛏️', t:'Bed'}, '+', {e:'🪨', t:'Rock'}], ans: 'Bedrock', ansIcon: '🪨' },
  { eq: [{e:'🛏️', t:'Bed'}, '+', {e:'🕰️', t:'Time'}], ans: 'Bedtime', ansIcon: '🛌' },
  { eq: [{e:'🛏️', t:'Bed'}, '+', {e:'🐛', t:'Bug'}], ans: 'Bedbug', ansIcon: '🐛' },
  { eq: [{e:'🌧️', t:'Rain'}, '+', {e:'🧥', t:'Coat'}], ans: 'Raincoat', ansIcon: '🧥' },
  { eq: [{e:'🌧️', t:'Rain'}, '+', {e:'💧', t:'Drop'}], ans: 'Raindrop', ansIcon: '💧' },
  { eq: [{e:'❄️', t:'Snow'}, '+', {e:'❄️', t:'Flake'}], ans: 'Snowflake', ansIcon: '❄️' },
  { eq: [{e:'🔥', t:'Fire'}, '+', {e:'👨', t:'Man'}], ans: 'Fireman', ansIcon: '👨‍🚒' },
  { eq: [{e:'👮', t:'Police'}, '+', {e:'👨', t:'Man'}], ans: 'Policeman', ansIcon: '👮‍♂️' },
  { eq: [{e:'🧊', t:'Ice'}, '+', {e:'🍦', t:'Cream'}], ans: 'Icecream', ansIcon: '🍦' },
  { eq: [{e:'🧊', t:'Ice'}, '+', {e:'🧊', t:'Cube'}], ans: 'Icecube', ansIcon: '🧊' },
  { eq: [{e:'👁️', t:'Eye'}, '+', {e:'🤨', t:'Brow'}], ans: 'Eyebrow', ansIcon: '🤨' },
  { eq: [{e:'👁️', t:'Eye'}, '+', {e:'💧', t:'Drop'}], ans: 'Eyedrop', ansIcon: '💧' },
  { eq: [{e:'👂', t:'Ear'}, '+', {e:'🎧', t:'Phone'}], ans: 'Earphone', ansIcon: '🎧' },
  { eq: [{e:'🗣️', t:'Speak'}, '+', {e:'🔈', t:'Er'}], ans: 'Speaker', ansIcon: '🔈' },
  { eq: [{e:'🦶', t:'Foot'}, '+', {e:'🐾', t:'Step'}], ans: 'Footstep', ansIcon: '👣' },
  { eq: [{e:'🦶', t:'Foot'}, '+', {e:'🖨️', t:'Print'}], ans: 'Footprint', ansIcon: '👣' },
  { eq: [{e:'✋', t:'Hand'}, '+', {e:'👜', t:'Bag'}], ans: 'Handbag', ansIcon: '👜' },
  { eq: [{e:'✋', t:'Hand'}, '+', {e:'🤝', t:'Shake'}], ans: 'Handshake', ansIcon: '🤝' },
  { eq: [{e:'⌚', t:'Wrist'}, '+', {e:'⌚', t:'Watch'}], ans: 'Wristwatch', ansIcon: '⌚' },
  { eq: [{e:'🧣', t:'Neck'}, '+', {e:'👔', t:'Tie'}], ans: 'Necktie', ansIcon: '👔' },
  { eq: [{e:'👦', t:'Boy'}, '+', {e:'👫', t:'Friend'}], ans: 'Boyfriend', ansIcon: '👫' },
  { eq: [{e:'👧', t:'Girl'}, '+', {e:'👭', t:'Friend'}], ans: 'Girlfriend', ansIcon: '👭' },
  { eq: [{e:'📚', t:'Book'}, '+', {e:'🔖', t:'Mark'}], ans: 'Bookmark', ansIcon: '🔖' },
  { eq: [{e:'📚', t:'Book'}, '+', {e:'🐛', t:'Worm'}], ans: 'Bookworm', ansIcon: '🐛' },
  { eq: [{e:'📚', t:'Book'}, '+', {e:'💼', t:'Case'}], ans: 'Bookcase', ansIcon: '🗄️' },
  { eq: [{e:'📓', t:'Note'}, '+', {e:'📚', t:'Book'}], ans: 'Notebook', ansIcon: '📓' },
  { eq: [{e:'🗞️', t:'News'}, '+', {e:'📄', t:'Paper'}], ans: 'Newspaper', ansIcon: '🗞️' },
  { eq: [{e:'🐮', t:'Cow'}, '+', {e:'🔔', t:'Bell'}], ans: 'Cowbell', ansIcon: '🔔' },
  { eq: [{e:'🏠', t:'House'}, '+', {e:'🚤', t:'Boat'}], ans: 'Houseboat', ansIcon: '🚤' },
  { eq: [{e:'🏠', t:'House'}, '+', {e:'🧹', t:'Work'}], ans: 'Housework', ansIcon: '🧹' },
  { eq: [{e:'🏠', t:'House'}, '+', {e:'🪰', t:'Fly'}], ans: 'Housefly', ansIcon: '🪰' },
  { eq: [{e:'🌳', t:'Tree'}, '+', {e:'🏠', t:'House'}], ans: 'Treehouse', ansIcon: '🛖' },
  { eq: [{e:'🐦', t:'Bird'}, '+', {e:'🏠', t:'House'}], ans: 'Birdhouse', ansIcon: '🐦' },
  { eq: [{e:'🐕', t:'Dog'}, '+', {e:'🏠', t:'House'}], ans: 'Doghouse', ansIcon: '🐕' },
  { eq: [{e:'🟩', t:'Green'}, '+', {e:'🏠', t:'House'}], ans: 'Greenhouse', ansIcon: '🏡' },
  { eq: [{e:'💡', t:'Light'}, '+', {e:'🏠', t:'House'}], ans: 'Lighthouse', ansIcon: '🗼' },
  { eq: [{e:'🔥', t:'Fire'}, '+', {e:'🏠', t:'House'}], ans: 'Firehouse', ansIcon: '🚒' },
  { eq: [{e:'🏫', t:'School'}, '+', {e:'🔔', t:'Bell'}], ans: 'Schoolbell', ansIcon: '🔔' },
  { eq: [{e:'🏫', t:'School'}, '+', {e:'🚌', t:'Bus'}], ans: 'Schoolbus', ansIcon: '🚌' },
  { eq: [{e:'🏫', t:'School'}, '+', {e:'🎒', t:'Bag'}], ans: 'Schoolbag', ansIcon: '🎒' },
  { eq: [{e:'🎒', t:'Back'}, '+', {e:'🎒', t:'Pack'}], ans: 'Backpack', ansIcon: '🎒' },
  { eq: [{e:'🚀', t:'Space'}, '+', {e:'🚢', t:'Ship'}], ans: 'Spaceship', ansIcon: '🛸' },
  { eq: [{e:'🚀', t:'Space'}, '+', {e:'👨', t:'Man'}], ans: 'Spaceman', ansIcon: '🧑‍🚀' },
  { eq: [{e:'⭐', t:'Star'}, '+', {e:'🚢', t:'Ship'}], ans: 'Starship', ansIcon: '🚀' },
  { eq: [{e:'☀️', t:'Sun'}, '+', {e:'🌅', t:'Rise'}], ans: 'Sunrise', ansIcon: '🌅' },
  { eq: [{e:'☀️', t:'Sun'}, '+', {e:'🌇', t:'Set'}], ans: 'Sunset', ansIcon: '🌇' },
  { eq: [{e:'☀️', t:'Sun'}, '+', {e:'🌞', t:'Day'}], ans: 'Sunday', ansIcon: '📅' },
  { eq: [{e:'🌙', t:'Moon'}, '+', {e:'🚶', t:'Walk'}], ans: 'Moonwalk', ansIcon: '🕺' },
  { eq: [{e:'🌎', t:'Earth'}, '+', {e:'🐛', t:'Worm'}], ans: 'Earthworm', ansIcon: '🪱' },
  { eq: [{e:'🌎', t:'Earth'}, '+', {e:'💥', t:'Quake'}], ans: 'Earthquake', ansIcon: '🏚️' },
  { eq: [{e:'🧠', t:'Brain'}, '+', {e:'🌩️', t:'Storm'}], ans: 'Brainstorm', ansIcon: '🤯' }
];

function initEmojiGuess(container, initialLevel, initialScore, saveProgress) {
  let currentLevel = initialLevel;
  let isAnimating = false;

  function render() {
    if (currentLevel > rebusData.length) {
      container.innerHTML = `<div class="hint-text" style="color: var(--success); font-size: 1.5rem; text-align: center;">অভিনন্দন! আপনি সবগুলো ${rebusData.length} লেভেল শেষ করেছেন।</div>`;
      return;
    }

    const levelData = rebusData[currentLevel - 1];
    
    // Generate 4 options: 1 correct, 3 random wrong
    let options = [{ e: levelData.ansIcon, t: levelData.ans }];
    let usedIndexes = new Set([currentLevel - 1]);
    
    while(options.length < 4) {
      let randIdx = Math.floor(Math.random() * rebusData.length);
      if(!usedIndexes.has(randIdx)) {
        usedIndexes.add(randIdx);
        options.push({ e: rebusData[randIdx].ansIcon, t: rebusData[randIdx].ans });
      }
    }
    // Shuffle options
    options.sort(() => 0.5 - Math.random());

    // Build Equation HTML
    let equationHtml = levelData.eq.map(item => {
      if (typeof item === 'string') {
        return `<span style="font-size: 2rem; font-weight: bold; margin: 0 10px; color: #e2e8f0; text-shadow: 0 0 5px rgba(255,255,255,0.5);">${item}</span>`;
      } else {
        return `
          <div style="display: flex; flex-direction: column; align-items: center; padding: 5px 10px;">
            <span style="font-size: 2.8rem; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.5));">${item.e}</span>
            <span style="font-size: 0.9rem; color: #cbd5e1; font-weight: 700; margin-top: 5px; text-transform: uppercase; letter-spacing: 1px;">${item.t}</span>
          </div>
        `;
      }
    }).join('');

    container.innerHTML = `
      <div style="width: 340px; border-radius: 24px; overflow: hidden; background: rgba(30, 41, 59, 0.9); border: 1px solid rgba(255,255,255,0.2); box-shadow: 0 20px 40px rgba(0,0,0,0.3), inset 0 0 20px rgba(255,255,255,0.05); position: relative; padding-bottom: 15px;">
        
        <!-- Header Section -->
        <div style="padding: 20px 20px 10px 20px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255,255,255,0.05);">
          <h2 style="color: #f8fafc; margin: 0; font-size: 1.2rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; text-shadow: 0 0 10px rgba(255,255,255,0.3);">✨ What is this?</h2>
          <div style="background: rgba(56, 189, 248, 0.2); border: 1px solid rgba(56, 189, 248, 0.4); padding: 4px 12px; border-radius: 20px; color: #38bdf8; font-weight: 800; font-size: 0.85rem; box-shadow: 0 0 10px rgba(56, 189, 248, 0.2);">
            LEVEL ${currentLevel}
          </div>
        </div>

        <!-- Display Area -->
        <div style="margin: 15px 20px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(255,255,255,0.25); border-radius: 20px; padding: 15px; display: flex; justify-content: center; align-items: center; box-shadow: inset 0 0 20px rgba(255,255,255,0.1), 0 8px 20px rgba(0,0,0,0.2);">
          ${equationHtml}
        </div>

        <!-- Target Question Box -->
        <div style="display: flex; justify-content: center; margin-bottom: 15px;">
          <div id="target-box" style="border: 2px dashed rgba(255,255,255,0.2); border-radius: 16px; width: 140px; height: 60px; display: flex; flex-direction: column; justify-content: center; align-items: center; font-size: 2rem; font-weight: bold; color: rgba(255,255,255,0.2); background: rgba(0,0,0,0.2); transition: all 0.3s ease;">
            ?
          </div>
        </div>

        <!-- Options Grid -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; padding: 0 20px;">
          ${options.map((opt, i) => `
            <div class="emoji-option" data-ans="${opt.t}" style="background: rgba(30, 41, 59, 0.8); border: 1px solid rgba(255,255,255,0.2); border-radius: 16px; padding: 10px 5px; display: flex; flex-direction: column; align-items: center; cursor: pointer; transition: all 0.2s ease; position: relative; box-shadow: 0 4px 15px rgba(0,0,0,0.15), inset 0 0 10px rgba(255,255,255,0.05);">
              <span style="font-size: 3rem; filter: drop-shadow(0 4px 4px rgba(0,0,0,0.4));">${opt.e}</span>
              <span style="font-size: 0.9rem; color: #f1f5f9; font-weight: 700; margin-top: 8px; letter-spacing: 0.5px;">${opt.t}</span>
              <div class="overlay-icon" style="position: absolute; top: -10px; right: -10px; font-size: 1.8rem; display: none; background: rgba(30,27,75,0.9); border-radius: 50%; padding: 2px; box-shadow: 0 4px 10px rgba(0,0,0,0.5);"></div>
            </div>
          `).join('')}
        </div>
      </div>
    `;

    // Add event listeners
    const optionCards = container.querySelectorAll('.emoji-option');
    optionCards.forEach(card => {
      const handleTap = (e) => {
        e.preventDefault();
        if (isAnimating) return;
        const selectedAns = card.getAttribute('data-ans');
        checkAnswer(selectedAns, card);
      };
      card.addEventListener('mousedown', handleTap);
      card.addEventListener('touchstart', handleTap, {passive: false});
      
      card.onmouseover = () => { 
        if(!isAnimating) {
          card.style.transform = 'translateY(-3px)'; 
          card.style.background = 'rgba(255,255,255,0.08)';
          card.style.borderColor = 'rgba(56, 189, 248, 0.5)';
          card.style.boxShadow = '0 8px 25px rgba(56, 189, 248, 0.2)';
        }
      };
      card.onmouseout = () => { 
        if(!isAnimating) {
          card.style.transform = 'translateY(0)'; 
          card.style.background = 'rgba(255,255,255,0.03)';
          card.style.borderColor = 'rgba(255,255,255,0.1)';
          card.style.boxShadow = '0 4px 15px rgba(0,0,0,0.2)';
        }
      };
    });

    function checkAnswer(selectedAns, cardElement) {
      isAnimating = true;
      const overlay = cardElement.querySelector('.overlay-icon');
      overlay.style.display = 'flex';
      overlay.style.alignItems = 'center';
      overlay.style.justifyContent = 'center';

      if (selectedAns === levelData.ans) {
        // Correct - Glowing Green
        overlay.innerHTML = '✅';
        cardElement.style.background = 'rgba(16, 185, 129, 0.15)';
        cardElement.style.borderColor = '#10b981';
        cardElement.style.boxShadow = '0 0 20px rgba(16, 185, 129, 0.3)';
        cardElement.style.transform = 'scale(1.05)';
        
        // Update target box with Neon style
        const targetBox = document.getElementById('target-box');
        targetBox.style.border = '2px solid #10b981';
        targetBox.style.background = 'rgba(16, 185, 129, 0.1)';
        targetBox.style.color = '#f8fafc';
        targetBox.style.boxShadow = '0 0 20px rgba(16, 185, 129, 0.2), inset 0 0 10px rgba(16, 185, 129, 0.1)';
        targetBox.innerHTML = `
          <span style="font-size: 2rem; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.5));">${levelData.ansIcon}</span>
          <span style="font-size: 0.9rem; font-weight: 800; letter-spacing: 1px; margin-top: 2px;">${levelData.ans}</span>
        `;
        
        // Confetti effect
        spawnConfetti(container);

        setTimeout(() => {
          currentLevel++;
          saveProgress(currentLevel, null);
          isAnimating = false;
          render();
        }, 1500);

      } else {
        // Wrong - Glowing Red
        overlay.innerHTML = '❌';
        cardElement.style.background = 'rgba(239, 68, 68, 0.15)';
        cardElement.style.borderColor = '#ef4444';
        cardElement.style.boxShadow = '0 0 20px rgba(239, 68, 68, 0.3)';
        
        // Shake animation
        cardElement.animate([
          { transform: 'translateX(0)' },
          { transform: 'translateX(-8px)' },
          { transform: 'translateX(8px)' },
          { transform: 'translateX(-8px)' },
          { transform: 'translateX(8px)' },
          { transform: 'translateX(0)' }
        ], { duration: 400 });

        setTimeout(() => {
          overlay.style.display = 'none';
          cardElement.style.background = 'rgba(255,255,255,0.03)';
          cardElement.style.borderColor = 'rgba(255,255,255,0.1)';
          cardElement.style.boxShadow = '0 4px 15px rgba(0,0,0,0.2)';
          cardElement.style.transform = 'scale(1)';
          isAnimating = false;
        }, 1000);
      }
    }
  }

  function spawnConfetti(parent) {
    for(let i=0; i<30; i++) {
      let conf = document.createElement('div');
      conf.style.position = 'absolute';
      conf.style.width = '10px';
      conf.style.height = '10px';
      conf.style.background = ['#ef4444', '#3b82f6', '#10b981', '#f59e0b', '#8b5cf6'][Math.floor(Math.random()*5)];
      conf.style.left = '50%';
      conf.style.top = '50%';
      conf.style.borderRadius = Math.random() > 0.5 ? '50%' : '0';
      parent.appendChild(conf);

      let angle = Math.random() * Math.PI * 2;
      let velocity = 50 + Math.random() * 100;
      let tx = Math.cos(angle) * velocity;
      let ty = Math.sin(angle) * velocity - 100;

      conf.animate([
        { transform: 'translate(0, 0) rotate(0deg)', opacity: 1 },
        { transform: `translate(${tx}px, ${ty}px) rotate(${Math.random()*360}deg)`, opacity: 0 }
      ], { duration: 1000 + Math.random()*500, easing: 'ease-out' });

      setTimeout(() => conf.remove(), 1500);
    }
  }

  render();
}
