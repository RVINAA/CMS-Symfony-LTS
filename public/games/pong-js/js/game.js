// variables globales
const DIRECCION = {
	Defecto: 0,
	Arriba: 1,
	Abajo: 2,
	Izquierda: 3,
	Derecha: 4
};

const TAMAÑOS = {
	TableroWidth: 1400,
	TableroHeight: 1000
}

const FONTS = {
	P30: '30px Courier New',
	P40: '40px Courier New',
	P50: '50px Courier New',
	P100: '100px Courier New'

}

const COLORES = {
	Tablero: '#3D4145',
	Blanco: '#FFFFFF'
}
const puntos = [5, 10, 15, 20, 25, 30, 35, 40, 45, 50];
const vidas = 3;
let vidasrestantes = vidas;
// cambia en cada ronda de forma random (funcion abajo)
const listaColores = ['#1abc9c', '#2ecc71', '#3498db', '#e74c3c', '#9b59b6'];

//objeto bola
let Bola = {
	new: function (incVelocidad) {
		return {
			width: 18,
			height: 18,
			x: (this.canvas.width / 2) - 9,
			y: (this.canvas.height / 2) - 9,
			moverX: DIRECCION.Defecto,
			moverY: DIRECCION.Defecto,
			velocidad: incVelocidad || 9
		};
	}
};

//objeto paddle, los palos
let Paddle = {
	new: function (lado) {
		return {
			width: 18,
			height: 70,
			x: lado === 'jugador' ? 150 : this.canvas.width - 150,
			y: (this.canvas.height / 2) - 35,
			puntuacion: 0,
			mover: DIRECCION.Defecto,
			velocidad: 10
		};
	}
};

let Pong = {
	inicializar: function () {
		this.canvas = document.querySelector('canvas');
		this.context = this.canvas.getContext('2d');

		//tamaño del tablero
		this.canvas.width = TAMAÑOS.TableroWidth;
		this.canvas.height = TAMAÑOS.TableroHeight;

		//creamos los jugadores y la bola
		this.jugador = Paddle.new.call(this, 'jugador');
		this.maquina = Paddle.new.call(this, 'maquina');
		this.bola = Bola.new.call(this);

		// velicidad de la maquina
		this.maquina.velocidad = 8;
		this.turno = this.maquina;

		this.enCurso = false;
		this.fin = false;
		this.ronda = 0;
		this.timer = 0;
		this.color = COLORES.Tablero;

		Pong.menu();
		Pong.listener();
	},

	menu: function () {
		// pinta todos los objetos en su estado actual
		Pong.pintar();

		// cambia el font y color del canvas
		this.context.font = FONTS.P50;
		this.context.fillStyle = this.color;

		this.context.fillRect(
			this.canvas.width / 2 - 350,
			this.canvas.height / 2 - 48,
			700,
			100
		);

		// cambia el color del texto inicial
		this.context.fillStyle = COLORES.Blanco;

		// texto inicial
		this.context.fillText(' ↑ , ↓ / W , S (para mover el paddle)',
			this.canvas.width / 2,
			this.canvas.height / 2 + 15
		);
	},
	
	menuFin: function () {
		this.context.font = FONTS.P50;
		this.context.fillStyle = this.color;
		this.context.fillRect(
			this.canvas.width / 2 - 350,
			this.canvas.height / 2 - 48,
			700,
			100
		);

		this.context.fillStyle = COLORES.Blanco;
        const texto = 'Has conseguido ' + this.jugador.puntuacion + ' punto(s)!';
		// Texto que aparece en el final del juego
		this.context.fillText(texto,
			this.canvas.width / 2,
			this.canvas.height / 2 + 15
        );

        // enviar puntuacion AJAX
        Pong.sendScoreAJAX(this.jugador.puntuacion);
	},

	//el listener de las teclas del usuario
	listener: function () {
        document.addEventListener('keydown', function (e) {
            // Para el menu al principio del juego
            if (Pong.enCurso === false) {
                Pong.enCurso = true;
                window.requestAnimationFrame(Pong.loop);
            }

            // fecha arriba / w
            if (e.keyCode === 38 || e.keyCode === 87) Pong.jugador.mover = DIRECCION.Arriba;

            // flecha abajo / s
            if (e.keyCode === 40 || e.keyCode === 83) Pong.jugador.mover = DIRECCION.Abajo;
        });

        // para cuando se deje de pulsar
        document.addEventListener('keyup', function (e) { Pong.jugador.mover = DIRECCION.Defecto; });
	},

	//el bucle principal
	loop: function () {
        Pong.update();
        Pong.pintar();

        // si el juego no ha terminado, pinta el siguiente frame
        if (!Pong.fin) requestAnimationFrame(Pong.loop);
        else {
            Pong.menuFin(); 
        }
	},
	
	// Pinta los objetos en el canvas
    pintar: function () {
        // Reset del canvas
        this.context.clearRect(
            0,
            0,
            this.canvas.width,
            this.canvas.height
        );

        // negro por defecto
        this.context.fillStyle = this.color;

        // pintar el fondo
        this.context.fillRect(
            0,
            0,
            this.canvas.width,
            this.canvas.height
        );

        // color del paddle y la bola
        this.context.fillStyle = COLORES.Blanco;

        // pinta jugadores
        this.context.fillRect(
            this.jugador.x,
            this.jugador.y,
            this.jugador.width,
            this.jugador.height
        );

        this.context.fillRect(
            this.maquina.x,
            this.maquina.y,
            this.maquina.width,
            this.maquina.height
        );

        // pinta la bola
        if (Pong.delayFinTurno.call(this)) {
            this.context.fillRect(
                this.bola.x,
                this.bola.y,
                this.bola.width,
                this.bola.height
            );
        }

        // pinta el separador del medio
        this.context.beginPath();
        this.context.setLineDash([7, 15]);
        this.context.moveTo((this.canvas.width / 2), this.canvas.height - 140);
        this.context.lineTo((this.canvas.width / 2), 140);
        this.context.lineWidth = 8;
        this.context.strokeStyle = COLORES.Blanco;
        this.context.stroke();

        // fuente por defecto y alineado al centro
        this.context.font = FONTS.P100;
        this.context.textAlign = 'center';

        // pinta la puntuacion de los jugadores
        this.context.fillText(
            this.jugador.puntuacion.toString(),
            (this.canvas.width / 2) - 300,
            200
		);
		
        this.context.fillText(
            this.maquina.puntuacion.toString(),
            (this.canvas.width / 2) + 300,
            200
        );

        // el font de la puntuacion
        this.context.font = FONTS.P30;

        this.context.fillText(
            'Ronda ' + (Pong.ronda + 1),
            (this.canvas.width / 2),
            35
        );

        // el font de las vidas restantes en el centro
        this.context.font = FONTS.P40;

        // pinta la ronda actual
        this.context.fillText(
            'Vidas restantes: ' + vidasrestantes,
            (this.canvas.width / 2),
            100
        );
	},

	// reinicia la bola, los jugadores
    reiniciarTurno: function(j1, j2) {
		this.bola = Bola.new.call(this, this.bola.velocidad);
		//el perdedor
        this.turno = j2;
        this.timer = (new Date()).getTime();

		//el ganador
        j1.puntuacion++;
	},
	
	// delay de 1sec al terminar un turno
    delayFinTurno: function() {
        return ((new Date()).getTime() - this.timer >= 1000);
	},
	
	// selecciona un fondo diferente para cada nivel
    generarColor: function () {
        let newColor = listaColores[Math.floor(Math.random() * listaColores.length)];
        if (newColor === this.color) return Pong.generarColor();
        return newColor;
	},

	//updade que sucede en cada frame
	update: function () {
        //si el juego no ha terminado
        if (!this.fin) {
            // colision de la bola con los limites del tablero
            if (this.bola.x <= 0) {
                Pong.reiniciarTurno.call(this, this.maquina, this.jugador);
                vidasrestantes--;
            }
            if (this.bola.x >= this.canvas.width - this.bola.width) Pong.reiniciarTurno.call(this, this.jugador, this.maquina);
            if (this.bola.y <= 0) this.bola.moverY = DIRECCION.Abajo;
            if (this.bola.y >= this.canvas.height - this.bola.height) this.bola.moverY = DIRECCION.Arriba;

            // movimiento del jugador
            if (this.jugador.mover === DIRECCION.Arriba) this.jugador.y -= this.jugador.velocidad;
            else if (this.jugador.mover === DIRECCION.Abajo) this.jugador.y += this.jugador.velocidad;

            // direccion de la bola cuando hace spawn despues de terminar un turno
            if (Pong.delayFinTurno.call(this) && this.turno) {
				//sale dependiendo de que jugador ha ganado/perdido el turno anterior
				this.bola.moverX = this.turno === this.jugador ? DIRECCION.Izquierda : DIRECCION.Derecha;
				//random si sale hacia arriba o hacia abajo
                this.bola.moverY = [DIRECCION.Arriba, DIRECCION.Abajo][Math.round(Math.random())];
                this.bola.y = Math.floor(Math.random() * this.canvas.height - 200) + 200;
                this.turno = null;
            }

            // el jugador cuando choca con los limites del tablero
            if (this.jugador.y <= 0) this.jugador.y = 0;
            else if (this.jugador.y >= (this.canvas.height - this.jugador.height)) this.jugador.y = (this.canvas.height - this.jugador.height);

            // mueve la bola con los valores de x , y
            if (this.bola.moverY === DIRECCION.Arriba) this.bola.y -= (this.bola.velocidad / 1.5);
            else if (this.bola.moverY === DIRECCION.Abajo) this.bola.y += (this.bola.velocidad / 1.5);
            if (this.bola.moverX === DIRECCION.Izquierda) this.bola.x -= this.bola.velocidad;
            else if (this.bola.moverX === DIRECCION.Derecha) this.bola.x += this.bola.velocidad;

            // los movimientos de la maquina
            if (this.maquina.y > this.bola.y - (this.maquina.height / 2)) {
                if (this.bola.moverX === DIRECCION.Derecha) this.maquina.y -= this.maquina.velocidad / 1.5;
                else this.maquina.y -= this.maquina.velocidad / 4;
            }
            if (this.maquina.y < this.bola.y - (this.maquina.height / 2)) {
                if (this.bola.moverX === DIRECCION.Derecha) this.maquina.y += this.maquina.velocidad / 1.5;
                else this.maquina.y += this.maquina.velocidad / 4;
            }

            // la maquina cuando choca con los limites del tablero
            if (this.maquina.y >= this.canvas.height - this.maquina.height) this.maquina.y = this.canvas.height - this.maquina.height;
            else if (this.maquina.y <= 0) this.maquina.y = 0;

            // colision del jugador con la bola
            if (this.bola.x - this.bola.width <= this.jugador.x && this.bola.x >= this.jugador.x - this.jugador.width) {
                if (this.bola.y <= this.jugador.y + this.jugador.height && this.bola.y + this.bola.height >= this.jugador.y) {
                    this.bola.x = (this.jugador.x + this.bola.width);
                    this.bola.moverX = DIRECCION.Derecha;
                }
            }

            // colision de la maquina con la bola
            if (this.bola.x - this.bola.width <= this.maquina.x && this.bola.x >= this.maquina.x - this.maquina.width) {
                if (this.bola.y <= this.maquina.y + this.maquina.height && this.bola.y + this.bola.height >= this.maquina.y) {
                    this.bola.x = (this.maquina.x - this.bola.width);
                    this.bola.moverX = DIRECCION.Izquierda;
                }
            }
		}
        
        // cada vez que el jugador obtiene 10 puntos, aumenta un poco la dificultad hasta un maximo de 10 veces
        if(Pong.ronda < 10) {
            if(this.jugador.puntuacion === puntos[Pong.ronda]) {
                this.color = this.generarColor();
                this.jugador.velocidad += 0.5;
                this.maquina.velocidad += 1;
                this.bola.velocidad += 1;
                Pong.ronda++;
            }
        }

        // cumprueba el fin del juego
        if (this.maquina.puntuacion === vidas) {
            this.fin = true;
        }
    },

	sendScoreAJAX: function (puntos) {
        $.ajax({
            type: "POST",
            url: "../score/add",
            data: { "Game": 'Pong JS', "Score" : puntos },
            success: (response) => {
                if (response.route) window.location.href = response.route;
            },
            error: () => {
                console.log("AJAX JQUERY DOES BRRRR.");
            }
        });
    }
};

document.addEventListener("DOMContentLoaded", Pong.inicializar(), false);