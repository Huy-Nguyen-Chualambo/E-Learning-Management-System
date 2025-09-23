{{-- filepath: resources/views/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Deha - E-Learning</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        .welcome-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            z-index: 10;
        }
        .gradient-text {
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .btn-gradient {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            transition: all 0.3s ease;
        }
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }
        .feature-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(45deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
        .floating-elements {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }
        .floating-elements::before,
        .floating-elements::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
        }
        .floating-elements::before {
            width: 300px;
            height: 300px;
            top: -150px;
            right: -150px;
            animation: float 6s ease-in-out infinite;
        }
        .floating-elements::after {
            width: 200px;
            height: 200px;
            bottom: -100px;
            left: -100px;
            animation: float 8s ease-in-out infinite reverse;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
    </style>
</head>
<body>
    <div class="floating-elements"></div>
    
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="welcome-card p-5">
                    <div class="text-center mb-5">
                        <div class="feature-icon mb-4">
                            <i class="fas fa-graduation-cap text-white fa-2x"></i>
                        </div>
                        <h1 class="display-3 fw-bold gradient-text mb-3">
                            Welcome to Deha E-Learning
                        </h1>
                        <p class="lead text-muted mb-4">Discover, Learn, and Experience with our Modern Learning Management System</p>
                    </div>

                    @auth
                        <div class="row mb-4">
                            <div class="col-md-8 mx-auto">
                                <div class="alert alert-success border-0 shadow-sm">
                                    <div class="d-flex align-items-center">
                                        <div class="feature-icon me-3" style="width: 50px; height: 50px; min-width: 50px;">
                                            <i class="fas fa-user-check text-white"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1">Welcome back!</h6>
                                            <small class="text-muted">Logged in as <strong>{{ Auth::user()->name }}</strong></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center">
                            <a href="{{ route('user.dashboard') }}" class="btn btn-gradient btn-lg px-5 py-3 me-3 mb-3">
                                <i class="fas fa-tachometer-alt me-2"></i>Go to Dashboard
                            </a>
                            @if(Auth::user()->hasRole('super-admin') || Auth::user()->hasRole('admin') || Auth::user()->hasRole('manager'))
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-danger btn-lg px-5 py-3 mb-3">
                                    <i class="fas fa-tools me-2"></i>Admin Panel
                                </a>
                            @endif
                        </div>
                    @else
                        <!-- Features Section -->
                        <div class="row mb-5">
                            <div class="col-md-4 text-center mb-4">
                                <div class="feature-icon position-relative overflow-hidden">
                                    <i class="fas fa-play text-white fa-lg"></i>
                                    <div class="position-absolute top-0 start-0 w-100 h-100 bg-white opacity-25 rounded-circle" style="transform: scale(0); transition: all 0.3s ease;"></div>
                                </div>
                                <h5 class="fw-bold">Smart Learning</h5>
                                <p class="text-muted">Personalized content for everyone</p>
                            </div>
                            <div class="col-md-4 text-center mb-4">
                                <div class="feature-icon position-relative overflow-hidden">
                                    <i class="fas fa-rocket text-white fa-lg"></i>
                                    <div class="position-absolute top-0 start-0 w-100 h-100 bg-white opacity-25 rounded-circle" style="transform: scale(0); transition: all 0.3s ease;"></div>
                                </div>
                                <h5 class="fw-bold">Fast & Modern</h5>
                                <p class="text-muted">Experience next-gen learning</p>
                            </div>
                            <div class="col-md-4 text-center mb-4">
                                <div class="feature-icon position-relative overflow-hidden">
                                    <i class="fas fa-star text-white fa-lg"></i>
                                    <div class="position-absolute top-0 start-0 w-100 h-100 bg-white opacity-25 rounded-circle" style="transform: scale(0); transition: all 0.3s ease;"></div>
                                </div>
                                <h5 class="fw-bold">Excellence</h5>
                                <p class="text-muted">Quality education delivered</p>
                            </div>
                        </div>

                        <style>
                        .feature-icon:hover .position-absolute {
                            transform: scale(1) !important;
                        }
                        .feature-icon {
                            transition: transform 0.3s ease;
                        }
                        .feature-icon:hover {
                            transform: scale(1.1);
                        }
                        </style>

                        <div class="text-center">
                            <h4 class="mb-4">Ready to start your learning journey?</h4>
                            <div class="d-flex flex-column flex-md-row gap-3 justify-content-center">
                                <a href="{{ route('login') }}" class="btn btn-gradient btn-lg px-5 py-3 text-white">
                                    <i class="fas fa-sign-in-alt me-2 text-white"></i>Login to Continue
                                </a>
                                <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg px-5 py-3">
                                    <i class="fas fa-user-plus me-2"></i>Create Account
                                </a>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Sakura Falling Effect 
<script>
    var stop, staticx;
    var img = new Image();
    img.src = "https://static.thenounproject.com/png/1565575-200.png"; // ảnh hoa anh đào PNG

    function Sakura(x, y, s, r, fn) {
        this.x = x; this.y = y; this.s = s; this.r = r; this.fn = fn;
    }
    Sakura.prototype.draw = function(cxt) {
        cxt.save();
        cxt.translate(this.x, this.y);
        cxt.rotate(this.r);
        cxt.drawImage(img, 0, 0, 40 * this.s, 40 * this.s);
        cxt.restore();
    };
    Sakura.prototype.update = function() {
        this.x = this.fn.x(this.x, this.y);
        this.y = this.fn.y(this.x, this.y);
        this.r = this.fn.r(this.r);
        if (this.x > window.innerWidth || this.x < 0 || this.y > window.innerHeight || this.y < 0) {
            if (Math.random() > 0.4) { this.x = getRandom('x'); this.y = 0; }
            else { this.x = window.innerWidth; this.y = getRandom('y'); }
            this.s = getRandom('s'); this.r = getRandom('r');
        }
    };

    function SakuraList() { this.list = []; }
    SakuraList.prototype.push = function(sakura) { this.list.push(sakura); };
    SakuraList.prototype.update = function() { for (var i=0;i<this.list.length;i++) this.list[i].update(); };
    SakuraList.prototype.draw = function(cxt) { for (var i=0;i<this.list.length;i++) this.list[i].draw(cxt); };

    function getRandom(option) {
        var ret, random;
        switch(option) {
            case 'x': ret = Math.random()*window.innerWidth; break;
            case 'y': ret = Math.random()*window.innerHeight; break;
            case 's': ret = Math.random(); break;
            case 'r': ret = Math.random()*5; break;
            case 'fnx': random=-0.5+Math.random(); ret=function(x){return x+0.5*random-1;}; break;
            case 'fny': random=0.5+Math.random()*0.5; ret=function(y){return y+random;}; break;
            case 'fnr': random=Math.random()*0.01; ret=function(r){return r+random;}; break;
        }
        return ret;
    }

    function startSakura() {
        var canvas=document.createElement('canvas'),cxt;
        staticx=true;
        canvas.height=window.innerHeight;
        canvas.width=window.innerWidth;
        canvas.setAttribute('style','position:fixed;left:0;top:0;pointer-events:none;z-index:2;');
        canvas.setAttribute('id','canvas_sakura');
        document.body.appendChild(canvas);
        cxt=canvas.getContext('2d');

        var sakuraList=new SakuraList();
        for (var i=0;i<50;i++) {
            var sakura=new Sakura(getRandom('x'),getRandom('y'),getRandom('s'),getRandom('r'),
                {x:getRandom('fnx'),y:getRandom('fny'),r:getRandom('fnr')});
            sakuraList.push(sakura);
        }
        (function animate() {
            cxt.clearRect(0,0,canvas.width,canvas.height);
            sakuraList.update();
            sakuraList.draw(cxt);
            stop=requestAnimationFrame(animate);
        })();
    }

    window.onresize=function(){
        var canvas=document.getElementById('canvas_sakura');
        if(canvas){canvas.width=window.innerWidth;canvas.height=window.innerHeight;}
    };

    img.onload=function(){ startSakura(); };
</script>-->
</body>
</html>
