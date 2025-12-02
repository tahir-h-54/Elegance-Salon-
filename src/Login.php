<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Elegance Login</title>

  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- GSAP for micro-animations -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>

  <style>
    /* Device frame + background to echo the reference image */
    :root{
      --bg-dark: #0b0b0d;
      --card-white: #ffffff;
      --muted: #9aa3ad;
    }

    html,body { height:100%; }
    body{
      margin:0;
      min-height:100vh;
      display:flex;
      align-items:center;
      justify-content:center;
      background: radial-gradient(ellipse at 50% 10%, rgba(255,255,255,0.02), rgba(0,0,0,0.85)), var(--bg-dark);
      font-family: Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
      -webkit-font-smoothing:antialiased;
      -moz-osx-font-smoothing:grayscale;
      padding:24px;
    }

    /* outer device look */
    .device {
      width:100%;
      max-width:980px;
      height:640px;
      border-radius:22px;
      padding:18px;
      background: linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.006));
      box-shadow:
        0 30px 60px rgba(0,0,0,0.6),
        inset 0 1px 0 rgba(255,255,255,0.02);
      display:flex;
      gap:18px;
      overflow:hidden;
    }

    /* Left card */
    .card {
      width:48%;
      min-width:320px;
      background: var(--card-white);
      border-radius:14px;
      padding:28px;
      box-shadow: 0 8px 30px rgba(2,6,23,0.18);
      display:flex;
      flex-direction:column;
      justify-content:flex-start;
    }

    /* Right visual panel */
    .visual {
      width:52%;
      border-radius:14px;
      overflow:hidden;
      position:relative;
      display:flex;
      align-items:stretch;
    }

    /* tiny UI details */
    .logo {
      display:flex;
      align-items:center;
      gap:12px;
      width: 50%;
    }
    /* small pill radio (sign in/sign up) */
    .pill-toggle {
      width:220px;
      background:#f1f5f9;
      padding:6px;
      border-radius:999px;
      display:flex;
      gap:6px;
      align-items:center;
      box-shadow: inset 0 1px 0 rgba(0,0,0,0.02);
      margin-top:14px;
    }
    .pill-toggle button {
      flex:1;
      padding:8px 12px;
      border-radius:999px;
      font-size:14px;
      border:0;
    }
    .pill-toggle button.active {
      background: #CFF752;
      color:#1b1b1b;
      box-shadow: 0 6px 18px rgba(31,156,240,0.28);
    }
    .pill-toggle button.inactive {
      background: transparent;
      color:#475569;
    }

    /* inputs look like image: light border, subtle inner */
    .form-input {
      width:100%;
      padding:12px 14px;
      border-radius:10px;
      border:1px solid #e6eef9;
      background: #fbfdff;
      outline:none;
      box-shadow: inset 0 1px 0 rgba(15,23,36,0.02);
    }
    .form-input::placeholder { color:#696969; }

    /* main big login button */
    .btn-primary {
      width:100%;
      padding:14px;
      border-radius:999px;
      background: #CFF752;
      color:#1b1b1b;
      font-weight:600;
      font-size:16px;
      border:0;
      box-shadow: 0 10px 30px rgba(15,132,255,0.18);
    }

    /* small tertiary button (outline) used by image but removed per request */
    .btn-outline {
      width:100%;
      padding:12px;
      border-radius:999px;
      border:1px solid rgba(15,23,36,0.06);
      background:transparent;
      color:#111827;
      font-weight:500;
    }

    /* subtle divider */
    .hr {
      height:1px;
      background:linear-gradient(90deg, transparent, rgba(15,23,36,0.04), transparent);
      margin:16px 0;
      border-radius:2px;
    }

    @media (max-width:900px){
      .device { flex-direction:column; height:auto; max-width:720px; }
      .card, .visual { width:100%; }
      .visual { height:260px; order: -1; }
      .card { order: 0; }
    }
  </style>
</head>
<body>

  <div class="device" role="main" aria-label="Login layout">
    <!-- Left card: the form -->
    <div class="card" id="card">
      <div>
        <div class="logo">
          <div class="pointer-events-none select-none">
            <img src="../images/elegance-saloon-logo-no-bg.png" alt="Elegance Logo">
          </div>
        </div>

        <!-- pill-like sign in / sign up buttons under heading -->
        <div class="pill-toggle" role="tablist" aria-label="Authentication mode">
          <button id="tabSignIn" class="active" role="tab" aria-selected="true">Sign in</button>
          <button id="tabSignUp" class="inactive" role="tab" aria-selected="false">Sign up</button>
        </div>
      </div>

      <!-- Form area -->
      <form id="authForm" class="mt-6 flex-1 flex flex-col justify-start gap-4" autocomplete="on">
        <div>
          <input id="email" type="email" required placeholder="Enter your email" class="form-input" />
        </div>

        <div>
          <input id="password" type="password" required placeholder="Enter your password" class="form-input" />
        </div>

        <div class="flex items-center justify-between text-sm" style="color:#64748b;">
          <label class="flex items-center gap-2">
            <input type="checkbox" class="rounded-sm" />
            <span>Remember me</span>
          </label>
          <a href="#" style="color:var(--primary); text-decoration:none;">Forgot Password?</a>
        </div>

        <div>
          <button type="submit" id="primaryBtn" class="btn-primary">Login</button>
        </div>

        <!-- kept layout space for alternatives (social buttons removed) -->
        <div class="hr" aria-hidden="true"></div>

        <div class="text-sm" style="color:#94a3b8;">
          Don't have an account? <button id="smallToggle" type="button" style="color:var(--primary); background:transparent; border:0; font-weight:600;">Create one</button>
        </div>

        <div style="font-size:12px;color:#bbc9d8;margin-top:12px;">
          By continuing you agree to our <span style="text-decoration:underline;color:#6b7280;">Terms</span> and <span style="text-decoration:underline;color:#6b7280;">Privacy Policy</span>.
        </div>
      </form>
    </div>

    <!-- Right panel: blue swirl visual -->
    <div class="visual" aria-hidden="true">
      <div class="swirl pointer-events-none select-none">
        <img src="../images/e3f27f701d467c0ba4656df6a28432e9.jpg" alt="">
        <!-- a translucent note box at bottom similar to image's right panel -->
        <div class="glass-note">
          <div style="font-weight:700;font-size:15px;">Beautiful geometries</div>
          <div style="margin-top:6px;font-size:13px;color:rgba(255,255,255,0.87);">
            A smooth, flowing background to help focus on the login form. This area is decorative and matches the original composition.
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Interactions: switching tabs (sign in / sign up), micro animations with GSAP
    const tabSignIn = document.getElementById('tabSignIn');
    const tabSignUp = document.getElementById('tabSignUp');
    const primaryBtn = document.getElementById('primaryBtn');
    const smallToggle = document.getElementById('smallToggle');
    const authForm = document.getElementById('authForm');

    let mode = 'signin'; // default as in image

    function setMode(m){
      mode = m;
      if(mode === 'signin'){
        tabSignIn.classList.add('active'); tabSignIn.classList.remove('inactive'); tabSignIn.setAttribute('aria-selected','true');
        tabSignUp.classList.remove('active'); tabSignUp.classList.add('inactive'); tabSignUp.setAttribute('aria-selected','false');
        primaryBtn.textContent = 'Login';
        smallToggle.textContent = 'Create one';
      } else {
        tabSignUp.classList.add('active'); tabSignUp.classList.remove('inactive'); tabSignUp.setAttribute('aria-selected','true');
        tabSignIn.classList.remove('active'); tabSignIn.classList.add('inactive'); tabSignIn.setAttribute('aria-selected','false');
        primaryBtn.textContent = 'Create account';
        smallToggle.textContent = 'Sign in';
      }
      // GSAP micro bounce for feedback
      gsap.fromTo(primaryBtn, {scale:0.98, y:6, opacity:0.95}, {scale:1, y:0, opacity:1, duration:0.45, ease:'power2.out'});
    }

    tabSignIn.addEventListener('click', ()=> setMode('signin'));
    tabSignUp.addEventListener('click', ()=> setMode('signup'));
    smallToggle.addEventListener('click', ()=> setMode(mode === 'signin' ? 'signup' : 'signin'));

    // tiny input focus glow
    document.querySelectorAll('.form-input').forEach(inp=>{
      inp.addEventListener('focus', ()=> gsap.to(inp, {boxShadow:'0 8px 30px rgba(31,156,240,0.12)', duration:0.28}));
      inp.addEventListener('blur', ()=> gsap.to(inp, {boxShadow:'none', duration:0.28}));
    });

    // fake submit to show animation
    authForm.addEventListener('submit', (e)=>{
      e.preventDefault();
      // press effect
      gsap.to(primaryBtn, {scale:0.98, duration:0.08, yoyo:true, repeat:1});
      // momentary glow
      gsap.to(primaryBtn, {boxShadow:'0 14px 40px rgba(16,185,129,0.12)', duration:0.45, ease:'power2.out'});
      setTimeout(()=> {
        gsap.to(primaryBtn, {boxShadow:'0 10px 30px rgba(15,132,255,0.18)', duration:0.45});
      },800);
    });

    // initial subtle entrance
    gsap.from('.card', {x:-40, opacity:0, duration:0.9, ease:'power3.out'});
    gsap.from('.swirl', {x:40, opacity:0, duration:0.9, ease:'power3.out', delay:0.08});
  </script>
</body>
</html>
