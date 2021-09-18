<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  
</head>

<body onload="Test1();"> 
{{-- <body> --}}

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <br><br>
                <div class="card">
                    
                    <div class="card-header">{{ __('Images') }}</div>

                    <div class="card-body">


                        <br>
                        <center>
                        <canvas id="canvas" width="400" height="400"></canvas>
                        </center>

                        <br>

                        <center>
                        <input type="text" name="title" id="x1">
                        <input type="text" name="title" id="y1">
                        <input type="text" name="title" id="x2">
                        <input type="text" name="title" id="y2">
                        </center>

                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="text-white" style="text-align:center">
            <a class="btn btn-primary" href="/Finish" role="button">Finish</a>
        </div>
    </div>

</body>
<script>

    function Test1() {

        var file = new File([""], "");
        // console.log(file);
        drawOnCanvas(file);

        var canvas = document.querySelector('canvas');

        var ctx = canvas.getContext('2d');
        const rect = (() => {
            var x1, y1, x2, y2;
            var show = false;
            function fix() {
                rect.x = Math.min(x1, x2);
                rect.y = Math.min(y1, y2);
                rect.w = Math.max(x1, x2) - Math.min(x1, x2);
                rect.h = Math.max(y1, y2) - Math.min(y1, y2);
            }
        
        

            function draw(ctx) { ctx.strokeRect(this.x, this.y, this.w, this.h) }
            const rect = {x : 0, y : 0, w : 0, h : 0,  draw};
            const API = {
                restart(point) {
                    x2 = x1 = point.x;
                    y2 = y1 = point.y;
                    fix();
                    show = true;
                },
                update(point) {
                    x2 = point.x;
                    y2 = point.y;
                    fix();
                    show = true;
                },
                toRect() {
                    show = false;
                    return Object.assign({}, rect);
                },
                draw(ctx) {
                    write(x1, y1, x2, y2);
                    if (show) { rect.draw(ctx) }
                },
                show : false,
            }
            return API;
        })();

        function write(x1,y1,x2,y2){

        var x1 = x1;
        var y1 = y1;
        var x2 = x2/2;
        var y2 = y2/2;
        document.getElementById('x1').value=x1;
        document.getElementById('y1').value=y1;
        document.getElementById('x2').value=x2;
        document.getElementById('y2').value=y2;

        };

        var drag = false;
        var img = new Image();  

        function drawOnCanvas(file) {
        var reader = new FileReader();

        reader.onload = function (e) {
            var dataURL = e.target.result;

            img.onload = function() {
            canvas.width = img.width*2;
            canvas.height = img.height*2;
            ctx.drawImage(img, 0, 0);
            };
            img.src = 'http://127.0.0.1:8000/storage/{{$image}}';
        };
        reader.readAsDataURL(file);  
        }

        requestAnimationFrame(mainLoop);
        const storedRects = [];
        const storedTags = [];
        //const baseImage = loadImage("https://www.w3schools.com/css/img_fjords.jpg");
        var refresh = true;

        // function loadImage(url) {
        //     const image = new Image();
        //     image.src = url;
        //     image.onload = () => refresh = true;
        //     return image;
        // }


        const mouse = {
            button : false,
            x : 0,
            y : 0,
            down : false,
            up : false,
            element : null,
            event(e) {
                const m = mouse;
                m.bounds = m.element.getBoundingClientRect();
                m.x = e.pageX - m.bounds.left - scrollX;
                m.y = e.pageY - m.bounds.top - scrollY;
                const prevButton = m.button;
                m.button = e.type === "mousedown" ? true : e.type === "mouseup" ? false : mouse.button;
                if (!prevButton && m.button) { m.down = true }
                if (prevButton && !m.button) { m.up = true }
            },
            start(element) {
                mouse.element = element;
                "down,up,move".split(",").forEach(name => document.addEventListener("mouse" + name, mouse.event));
            }
        }

        mouse.start(canvas);
        function draw() {
            ctx.drawImage(img, 0, 0, ctx.canvas.width, ctx.canvas.height);
            ctx.lineWidth = 2;
            ctx.strokeStyle = "red";
            storedRects.forEach(rect => rect.draw(ctx));
            ctx.strokeStyle = "red";
            rect.draw(ctx);
        }

        function mainLoop() {
            if (refresh || mouse.down || mouse.up || mouse.button) {
                refresh = true;
                if (mouse.down) {
                    mouse.down = false;
                    rect.restart(mouse);
                } else if (mouse.button) {
                    rect.update(mouse);
                } else if (mouse.up) {
                    mouse.up = false;
                    rect.update(mouse);
                    storedRects.push(rect.toRect());
                }
                draw();
            }
            requestAnimationFrame(mainLoop)
        }
    }
</script>
</html>
