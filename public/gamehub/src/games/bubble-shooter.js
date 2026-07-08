function initBubbleShooter(container, initialLevel, initialScore, saveProgress) {
  let currentLevel = initialLevel;
  let score = initialScore || 0;
  
  // Removed cursor: crosshair
  container.innerHTML = `
    <div style="display: flex; justify-content: space-between; width: 100%; max-width: 320px; margin-bottom: 15px; background: rgba(255,255,255,0.05); padding: 10px 20px; border-radius: 20px; border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
      <span style="font-weight: bold; color: #38bdf8; text-shadow: 0 0 5px rgba(56,189,248,0.5); font-size: 1.1rem;">Score: <span id="bs-score" style="color: white;">${score}</span></span>
      <span style="font-weight: bold; color: #f59e0b; text-shadow: 0 0 5px rgba(245,158,11,0.5); font-size: 1.1rem;">Target: <span id="bs-target" style="color: white;">0</span></span>
    </div>
    <div style="position: relative; width: 320px; height: 460px; border-radius: 16px; overflow: hidden; background: rgba(30, 41, 59, 0.9); border: 1px solid rgba(255,255,255,0.2); box-shadow: 0 15px 35px rgba(0,0,0,0.3), inset 0 0 20px rgba(255,255,255,0.05);">
      <canvas id="bs-canvas" width="320" height="460" style="position: absolute; top:0; left:0; touch-action: none;"></canvas>
    </div>
    <div id="bs-msg" style="margin-top: 15px; font-weight: bold; height: 20px; color: #10b981; text-align: center; font-size: 1.1rem; text-shadow: 0 0 10px rgba(16, 185, 129, 0.5);"></div>
    <div style="margin-top: 15px; font-size: 0.85rem; color: #cbd5e1; text-align: center; max-width: 320px; background: rgba(0,0,0,0.2); padding: 10px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
      💡 টিপস: স্ক্রিনে ড্র্যাগ (Drag) করে টার্গেট ফিক্স করুন এবং ছেড়ে দিন। কর্নারের বাবল ট্যাপ করে কালার পাল্টাতে পারবেন!
    </div>
  `;

  const canvas = document.getElementById('bs-canvas');
  const ctx = canvas.getContext('2d');
  const msgEl = document.getElementById('bs-msg');
  const scoreEl = document.getElementById('bs-score');
  const targetEl = document.getElementById('bs-target');

  const RADIUS = 14;
  const DIAM = 28;
  const ROW_HEIGHT = 24; 
  const OFFSET_X = 6;
  const OFFSET_Y = 6;
  const MAX_ROWS = 16;
  
  let grid = [];
  let colors = ['#ef4444', '#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899'];
  let currentColors = [];
  
  let bullet = null;
  let nextColor = '';
  let particles = [];
  let poppingBubbles = [];
  
  let targetScore = 0;
  let levelRows = 0;
  let isAnimating = false;
  let loopId = null;
  
  // Aiming Variables
  let isAiming = false;
  let aimAngle = -Math.PI / 2;

  function initLevel() {
    if (currentLevel > 100) {
      msgEl.innerText = "অভিনন্দন! আপনি ১০০ লেভেল শেষ করেছেন!";
      return;
    }
    
    let colorCount = Math.min(3 + Math.floor((currentLevel - 1) / 10), colors.length);
    currentColors = colors.slice(0, colorCount);
    
    levelRows = Math.min(3 + Math.floor((currentLevel - 1) / 5), 8); 
    targetScore = score + (levelRows * 11 * 20) + (currentLevel * 100);
    
    targetEl.innerText = targetScore;
    msgEl.innerText = '';
    particles = [];
    poppingBubbles = [];
    isAiming = false;
    
    grid = [];
    for (let r = 0; r < MAX_ROWS; r++) {
      let colsInRow = (r % 2 === 0) ? 11 : 10;
      let row = [];
      for (let c = 0; c < colsInRow; c++) {
        if (r < levelRows) {
          row.push({ color: currentColors[Math.floor(Math.random() * currentColors.length)], scale: 1 });
        } else {
          row.push(null);
        }
      }
      grid.push(row);
    }
    
    reloadBullet();
    if(loopId) cancelAnimationFrame(loopId);
    loopId = requestAnimationFrame(gameLoop);
  }

  function getBubbleCenter(r, c) {
    let x = OFFSET_X + c * DIAM + RADIUS;
    if (r % 2 === 1) x += RADIUS;
    let y = OFFSET_Y + r * ROW_HEIGHT + RADIUS;
    return {x, y};
  }

  function reloadBullet() {
    nextColor = currentColors[Math.floor(Math.random() * currentColors.length)];
    bullet = {
      x: canvas.width / 2,
      y: canvas.height - 25,
      color: currentColors[Math.floor(Math.random() * currentColors.length)],
      vx: 0, vy: 0, moving: false
    };
    isAiming = false;
  }

  function gameLoop() {
    if (!document.body.contains(container)) return;
    update();
    draw();
    loopId = requestAnimationFrame(gameLoop);
  }

  function update() {
    for (let i = particles.length - 1; i >= 0; i--) {
      let p = particles[i];
      p.x += p.vx;
      p.y += p.vy;
      p.vy += 0.15; 
      p.life -= p.decay;
      if (p.life <= 0) particles.splice(i, 1);
    }

    for (let i = poppingBubbles.length - 1; i >= 0; i--) {
      let pb = poppingBubbles[i];
      let b = grid[pb.r][pb.c];
      if (b && b.popping) {
        b.scale -= 0.15;
        if (b.scale <= 0) {
          grid[pb.r][pb.c] = null;
          poppingBubbles.splice(i, 1);
        }
      } else {
        poppingBubbles.splice(i, 1);
      }
    }

    if (bullet && bullet.moving) {
      bullet.x += bullet.vx;
      bullet.y += bullet.vy;
      
      if (bullet.x <= RADIUS) {
        bullet.x = RADIUS;
        bullet.vx *= -1;
      } else if (bullet.x >= canvas.width - RADIUS) {
        bullet.x = canvas.width - RADIUS;
        bullet.vx *= -1;
      }
      
      if (checkCollision()) {
        snapBulletToGrid();
      }
    }
  }

  function checkCollision() {
    if (bullet.y <= OFFSET_Y + RADIUS) return true; 
    
    for (let r = 0; r < grid.length; r++) {
      for (let c = 0; c < grid[r].length; c++) {
        if (grid[r][c] && !grid[r][c].popping) {
          let center = getBubbleCenter(r, c);
          let dist = Math.hypot(bullet.x - center.x, bullet.y - center.y);
          if (dist <= DIAM - 2) {
            return true;
          }
        }
      }
    }
    return false;
  }

  function snapBulletToGrid() {
    bullet.moving = false;
    let closestDist = Infinity;
    let bestR = 0, bestC = 0;
    
    for (let r = 0; r < grid.length; r++) {
      for (let c = 0; c < grid[r].length; c++) {
        if (!grid[r][c]) {
          let center = getBubbleCenter(r, c);
          let dist = Math.hypot(bullet.x - center.x, bullet.y - center.y);
          if (dist < closestDist) {
            closestDist = dist;
            bestR = r;
            bestC = c;
          }
        }
      }
    }
    
    if (bestR >= MAX_ROWS - 2) {
      gameOver();
      return;
    }
    
    grid[bestR][bestC] = { color: bullet.color, scale: 1 };
    checkMatches(bestR, bestC);
  }

  function getNeighbors(row, col) {
    let neighbors = [];
    let maxCols = (row % 2 === 0) ? 11 : 10;
    
    if (col > 0) neighbors.push({r: row, c: col - 1});
    if (col < maxCols - 1) neighbors.push({r: row, c: col + 1});
    
    for (let r of [row - 1, row + 1]) {
      if (r < 0 || r >= grid.length) continue;
      let mC = (r % 2 === 0) ? 11 : 10;
      if (row % 2 === 0) { 
        if (col - 1 >= 0) neighbors.push({r, c: col - 1});
        if (col < mC) neighbors.push({r, c: col});
      } else { 
        if (col < mC) neighbors.push({r, c: col});
        if (col + 1 < mC) neighbors.push({r, c: col + 1});
      }
    }
    return neighbors;
  }

  function checkMatches(r, c) {
    let targetColor = grid[r][c].color;
    let toRemove = [];
    let visited = new Set();
    
    function dfs(row, col) {
      let key = row + ',' + col;
      if (visited.has(key)) return;
      if (!grid[row][col] || grid[row][col].color !== targetColor) return;
      
      visited.add(key);
      toRemove.push({r: row, c: col});
      
      let neighbors = getNeighbors(row, col);
      for (let n of neighbors) dfs(n.r, n.c);
    }
    
    dfs(r, c);
    
    if (toRemove.length >= 3) {
      toRemove.forEach(p => {
        poppingBubbles.push(p);
        if (grid[p.r][p.c]) grid[p.r][p.c].popping = true;
        let center = getBubbleCenter(p.r, p.c);
        spawnParticles(center.x, center.y, targetColor);
      });
      score += toRemove.length * 20;
      scoreEl.innerText = score;
      
      setTimeout(cleanupFloating, 250);
    } else {
      checkBottomLimit();
    }
  }

  function spawnParticles(x, y, color) {
    for(let i=0; i<8; i++) {
      let angle = Math.random() * Math.PI * 2;
      let speed = 2 + Math.random() * 4;
      particles.push({
        x, y, color,
        vx: Math.cos(angle) * speed,
        vy: Math.sin(angle) * speed,
        life: 1.0, decay: 0.03 + Math.random()*0.04
      });
    }
  }

  function cleanupFloating() {
    let visited = new Set();
    
    function dfsReachTop(row, col) {
      let key = row + ',' + col;
      if (visited.has(key)) return;
      if (!grid[row][col] || grid[row][col].popping) return; 
      visited.add(key);
      
      let neighbors = getNeighbors(row, col);
      for (let n of neighbors) dfsReachTop(n.r, n.c);
    }
    
    for (let c = 0; c < 11; c++) {
      if (grid[0][c]) dfsReachTop(0, c);
    }
    
    let dropped = 0;
    for (let r = 0; r < grid.length; r++) {
      for (let c = 0; c < grid[r].length; c++) {
        if (grid[r][c] && !visited.has(r + ',' + c) && !grid[r][c].popping) {
          let center = getBubbleCenter(r, c);
          spawnParticles(center.x, center.y, grid[r][c].color);
          grid[r][c] = null; 
          dropped++;
        }
      }
    }
    
    if (dropped > 0) {
      score += dropped * 30; 
      scoreEl.innerText = score;
    }

    if (score >= targetScore && !isAnimating) {
      levelComplete();
    } else {
      checkBottomLimit();
    }
  }

  function checkBottomLimit() {
    let reachedBottom = false;
    for (let c = 0; c < 11; c++) {
      if (grid[MAX_ROWS - 2][c]) reachedBottom = true;
    }
    if (reachedBottom) {
      gameOver();
      return;
    }
    reloadBullet();
  }

  function levelComplete() {
    msgEl.style.color = "var(--success)";
    msgEl.innerText = `লেভেল ${currentLevel} সম্পন্ন!`;
    isAnimating = true;
    setTimeout(() => {
      isAnimating = false;
      currentLevel++;
      saveProgress(currentLevel, score);
      initLevel();
    }, 1500);
  }

  function gameOver() {
    msgEl.style.color = "var(--danger)";
    msgEl.innerText = "গেম ওভার! বাবল নিচে নেমে গেছে।";
    isAnimating = true;
    setTimeout(() => {
      isAnimating = false;
      score = initialScore || 0;
      initLevel();
    }, 2000);
  }

  // Utility to create a darker shade for the gradient
  function shadeColor(color, percent) {
    let R = parseInt(color.substring(1,3),16);
    let G = parseInt(color.substring(3,5),16);
    let B = parseInt(color.substring(5,7),16);
    R = parseInt(R * (100 + percent) / 100);
    G = parseInt(G * (100 + percent) / 100);
    B = parseInt(B * (100 + percent) / 100);
    R = (R<255)?R:255; R = (R>0)?R:0;
    G = (G<255)?G:255; G = (G>0)?G:0;
    B = (B<255)?B:255; B = (B>0)?B:0;
    let RR = ((R.toString(16).length==1)?"0"+R.toString(16):R.toString(16));
    let GG = ((G.toString(16).length==1)?"0"+G.toString(16):G.toString(16));
    let BB = ((B.toString(16).length==1)?"0"+B.toString(16):B.toString(16));
    return "#"+RR+GG+BB;
  }

  function draw() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    
    // Draw Grid
    for (let r = 0; r < grid.length; r++) {
      for (let c = 0; c < grid[r].length; c++) {
        if (grid[r][c]) {
          let center = getBubbleCenter(r, c);
          drawBubble(center.x, center.y, grid[r][c].color, RADIUS * grid[r][c].scale);
        }
      }
    }
    
    // Draw Aiming Trajectory Line (Dotted Line)
    if (isAiming && bullet && !bullet.moving) {
      ctx.beginPath();
      ctx.setLineDash([6, 6]);
      ctx.strokeStyle = 'rgba(255, 255, 255, 0.6)';
      ctx.lineWidth = 2;
      
      let lx = bullet.x;
      let ly = bullet.y;
      let dx = Math.cos(aimAngle);
      let dy = Math.sin(aimAngle);
      
      ctx.moveTo(lx, ly);
      // Simulate raycast for 50 steps
      for(let i=0; i<45; i++) {
         lx += dx * 10;
         ly += dy * 10;
         if (lx <= RADIUS || lx >= canvas.width - RADIUS) {
           dx *= -1; // Bounce visually
         }
         ctx.lineTo(lx, ly);
      }
      ctx.stroke();
      ctx.setLineDash([]);
    }
    
    // Draw Shooter Base
    ctx.fillStyle = 'rgba(255,255,255,0.05)';
    ctx.beginPath();
    ctx.arc(canvas.width/2, canvas.height - 25, RADIUS + 10, 0, Math.PI*2);
    ctx.fill();
    ctx.strokeStyle = 'rgba(255,255,255,0.2)';
    ctx.lineWidth = 2;
    ctx.stroke();
    
    // Draw Next Color Indicator (Swap Bubble) - Pulse Effect
    let pulse = 1 + Math.sin(Date.now() / 200) * 0.1;
    drawBubble(canvas.width - 25, canvas.height - 25, nextColor, 12 * pulse);
    
    // Draw Bullet
    if (bullet && !bullet.moving) {
      drawBubble(bullet.x, bullet.y, bullet.color, RADIUS);
    } else if (bullet && bullet.moving) {
      ctx.fillStyle = bullet.color;
      ctx.globalAlpha = 0.3;
      ctx.beginPath();
      ctx.arc(bullet.x - bullet.vx*1.5, bullet.y - bullet.vy*1.5, RADIUS*0.8, 0, Math.PI*2);
      ctx.fill();
      ctx.globalAlpha = 1.0;
      drawBubble(bullet.x, bullet.y, bullet.color, RADIUS);
    }
    
    // Draw Particles
    for (let p of particles) {
      ctx.globalAlpha = Math.max(0, p.life);
      ctx.fillStyle = p.color;
      ctx.beginPath();
      ctx.arc(p.x, p.y, 4, 0, Math.PI*2);
      ctx.fill();
    }
    ctx.globalAlpha = 1.0;
  }

  function drawBubble(x, y, color, r) {
    if (r <= 0) return;
    
    let grad = ctx.createRadialGradient(x - r/4, y - r/4, r/8, x, y, r);
    grad.addColorStop(0, '#ffffff'); 
    grad.addColorStop(0.3, color);
    grad.addColorStop(1, shadeColor(color, -25)); 
    
    ctx.fillStyle = grad;
    ctx.beginPath();
    ctx.arc(x, y, r, 0, Math.PI * 2);
    ctx.fill();
    
    ctx.strokeStyle = 'rgba(0,0,0,0.15)';
    ctx.lineWidth = 1;
    ctx.stroke();
  }

  function getMouseAngle(clientX, clientY) {
    const rect = canvas.getBoundingClientRect();
    const scaleX = canvas.width / rect.width;
    const scaleY = canvas.height / rect.height;
    const mouseX = (clientX - rect.left) * scaleX;
    const mouseY = (clientY - rect.top) * scaleY;
    
    if (Math.hypot(mouseX - (canvas.width - 25), mouseY - (canvas.height - 25)) < 30) {
      return 'SWAP';
    }
    
    if (mouseY >= bullet.y - 15) return null; 
    return Math.atan2(mouseY - bullet.y, mouseX - bullet.x);
  }

  function handleStart(clientX, clientY) {
    if (isAnimating || !bullet || bullet.moving) return;
    let angle = getMouseAngle(clientX, clientY);
    
    if (angle === 'SWAP') {
      let temp = bullet.color;
      bullet.color = nextColor;
      nextColor = temp;
      return;
    }
    
    if (angle !== null) {
      isAiming = true;
      aimAngle = angle;
    }
  }

  function handleMove(clientX, clientY) {
    if (!isAiming) return;
    let angle = getMouseAngle(clientX, clientY);
    if (angle !== null && angle !== 'SWAP') {
      aimAngle = angle;
    }
  }

  function handleEnd() {
    if (!isAiming || isAnimating || !bullet || bullet.moving) return;
    isAiming = false;
    const speed = 18; 
    bullet.vx = Math.cos(aimAngle) * speed;
    bullet.vy = Math.sin(aimAngle) * speed;
    bullet.moving = true;
  }

  // Mouse Events
  canvas.addEventListener('mousedown', (e) => handleStart(e.clientX, e.clientY));
  canvas.addEventListener('mousemove', (e) => handleMove(e.clientX, e.clientY));
  canvas.addEventListener('mouseup', handleEnd);
  canvas.addEventListener('mouseleave', () => { if(isAiming) isAiming = false; });

  // Touch Events
  canvas.addEventListener('touchstart', (e) => {
    e.preventDefault();
    handleStart(e.touches[0].clientX, e.touches[0].clientY);
  }, {passive: false});
  canvas.addEventListener('touchmove', (e) => {
    e.preventDefault();
    handleMove(e.touches[0].clientX, e.touches[0].clientY);
  }, {passive: false});
  canvas.addEventListener('touchend', (e) => {
    e.preventDefault();
    handleEnd();
  }, {passive: false});

  initLevel();
  
  const observer = new MutationObserver(() => {
    if (!document.body.contains(container) || container.innerHTML === '') {
      if(loopId) cancelAnimationFrame(loopId);
      observer.disconnect();
    }
  });
  observer.observe(container, { childList: true, subtree: true });
}
