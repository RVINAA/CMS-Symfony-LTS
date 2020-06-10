const DIRECCION = {
    Defecto: 0,
	Arriba: 1,
	Abajo: 2,
	Izquierda: 3,
	Derecha: 4
};

const TAMAÑOS = {
    Grid: 20,
    Cuadro: 20 // 20 x 20 = 400
}

const FONTS = {
    Courier: '50px Courier New',

    P20: '20px Courier New',
}

const COLORES = {
	Snake: 'green',
    Manzana: 'red',
    Blanco: '#FFFFFF',
    Negro: '#000000',
    Gris: '#D3D3D3'
}

const FPS = 10;
let bucle;
let Snake = {
    new: function () {
		return {
            cola:[],
            tamaño: 1,
            direccion: DIRECCION.Defecto,
            moverX: 1,
            moverY: 0,
            x: 10,
            y: 10,
            puntuacion: 0
		};
	}
};

let Manzana = {
    new: function () {
		return {
			x: 15,
			y: 15
		};
	}
};

let Juego = {
    inicializar: function () {
        this.canvas = document.querySelector('canvas');
        this.context = this.canvas.getContext('2d');
        
        this.snake = Snake.new.call(this);
        this.manzana = Manzana.new.call(this);

        this.enCurso = false;
        this.fin = false;
        
        this.puntuacion = 0;

        Juego.menu();
        Juego.listener();
    },

    menu: function () {
		// cambia fondo, el font y color del canvas
		this.context.fillStyle = COLORES.Gris;

		this.context.fillRect(
			0,
			0,
			400,
			400
        );
        this.context.font = FONTS.P20;
        this.context.fillStyle = COLORES.Negro;
        this.context.textAlign = 'center';

		// texto inicial
		this.context.fillText(' ↑ , ↓ , ← , → (para mover)',
			200,
			200
		);
	},
	
	menuFin: function () {
        // cambia fondo, el font y color del canvas
		this.context.fillStyle = COLORES.Gris;

		this.context.fillRect(
			0,
			0,
			400,
			400
        );
        this.context.font = FONTS.P20;
        this.context.fillStyle = COLORES.Negro;
        this.context.textAlign = 'center';
        
        const texto = 'Has conseguido ' + this.snake.puntuacion + ' punto(s)!';
		// texto inicial
		this.context.fillText(texto,
			200,
			200
        );

        // enviar puntuacion AJAX
        Juego.sendScoreAJAX(this.snake.puntuacion);
        
	},
    
    //el bucle principal
    loop: function () {
        Juego.pintarUpdate();

        if(Juego.fin) {
            clearInterval(bucle);
            setTimeout(Juego.menuFin(), 1000);
            
        }
    },
    
    listener: function () {

        document.addEventListener('keydown', function (e) {
            // Para el menu al principio del juego
            if (Juego.enCurso === false) {
                Juego.enCurso = true;
                if(!Juego.fin) {
                    bucle = setInterval(Juego.loop, 1000 / FPS);
                }
            }
            
            // fecha arriba / W
            if (e.keyCode === 38 || e.keyCode === 87) {
                if (Juego.snake.direccion != DIRECCION.Abajo) {
                    Juego.snake.direccion = DIRECCION.Arriba;
                    Juego.snake.moverX = 0;
                    Juego.snake.moverY = -1;
                }
            }

            // flecha abajo / S
            if (e.keyCode === 40 || e.keyCode === 83) {
                if (Juego.snake.direccion != DIRECCION.Arriba) {
                    Juego.snake.direccion = DIRECCION.Abajo;
                    Juego.snake.moverX = 0;
                    Juego.snake.moverY = 1;
                }
            }

            // flecha izquierda / A
            if (e.keyCode === 37 || e.keyCode === 65) {
                if (Juego.snake.direccion != DIRECCION.Derecha) {
                    Juego.snake.direccion = DIRECCION.Izquierda;
                    Juego.snake.moverX = -1;
                    Juego.snake.moverY = 0;

                }
            }

            // flecha derecha / D
            if (e.keyCode === 39 || e.keyCode === 68) {
                if (Juego.snake.direccion != DIRECCION.Izquierda) {
                    Juego.snake.direccion = DIRECCION.Derecha;
                    Juego.snake.moverX = 1;
                    Juego.snake.moverY = 0;
                }
            }
        });
    },

    //pinta y actualiza que sucede en cada frame
    pintarUpdate: function() {
        //si el juego no ha terminado
        if (!this.fin) {
            //movimiento del snake
            this.snake.x += this.snake.moverX;
            this.snake.y += this.snake.moverY;

            //al comer la manzana
            if (this.snake.x == this.manzana.x && this.snake.y == this.manzana.y) {
                //aumenta el tamaño de la cola
                this.snake.tamaño++;
                this.snake.puntuacion += 10;
                //actualizar puntuacion
                document.querySelector('#puntuacion').textContent = (this.snake.puntuacion).toString().padStart(8,'0');
                //cambia las coordenadas de la manzana
                this.manzana.x = Math.floor(Math.random() * TAMAÑOS.Grid);
                this.manzana.y = Math.floor(Math.random() * TAMAÑOS.Grid);
            }
            
            // colision con los bordes
            if (this.snake.x < 0 
                || this.snake.y < 0 
                || this.snake.x > TAMAÑOS.Grid - 1 
                || this.snake.y > TAMAÑOS.Grid - 1) {
                this.fin = true;
            }

            //pinta el fondo
            this.context.fillStyle = COLORES.Gris;
            this.context.fillRect(0, 0, canvas.width, canvas.height);

            // pinta snake
            this.context.fillStyle = "green";
            for (let i = 0; i < this.snake.cola.length; i++) {
                this.context.fillRect(
                this.snake.cola[i].x * TAMAÑOS.Cuadro,
                this.snake.cola[i].y * TAMAÑOS.Cuadro,
                TAMAÑOS.Cuadro,
                TAMAÑOS.Cuadro
                );

                //colision con la cola
                if (this.snake.cola[i].x == this.snake.x && this.snake.cola[i].y == this.snake.y) {
                    this.fin = true;
                }
            }

            // pinta manzana
            this.context.fillStyle = COLORES.Manzana;
            this.context.fillRect(this.manzana.x * TAMAÑOS.Cuadro, this.manzana.y * TAMAÑOS.Cuadro, TAMAÑOS.Cuadro, TAMAÑOS.Cuadro);

            //set snake cola
            this.snake.cola.push({ x: this.snake.x, y: this.snake.y });
            while (this.snake.cola.length > this.snake.tamaño) {
                this.snake.cola.shift();
            }
            
        }
    },

    sendScoreAJAX: function (puntos) {
        $.ajax({
            type: "POST",
            url: "../score/add",
            data: { "Game": 'Snake JS', "Score" : puntos },
            success: (response) => {
                if (response.route) window.location.href = response.route;
            },
            error: () => {
                console.log("AJAX JQUERY DOES BRRRR.");
            }
        });
    }
};

document.addEventListener("DOMContentLoaded", Juego.inicializar(), false);