<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController
{
    public static function login(Router $router)
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        }

        //Render a la vista
        $router->render('auth/login', [
            'titulo' => 'Iniciar Sesión'
        ]);
    }

    public static function logout()
    {
        echo "Desde Logout";
    }

    public static function crear(Router $router)
    {
        $alertas = [];
        $usuario = new Usuario;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            $existeUsuario = Usuario::where('email', $usuario->email);

            if (empty($alertas)) {
                if ($existeUsuario) {
                    Usuario::setAlerta('error', 'El Usuario ya está registrado');
                    $alertas = Usuario::getAlertas();
                } else {
                    //Hashear password
                    $usuario->hashPassword();

                    //Eliminar Password 2
                    unset($usuario->password2);

                    //Generar Token
                    $usuario->crearToken();

                    //Crear nuevo usuario
                    $resultado = $usuario->guardar();

                    //Enviar Email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();


                    if ($resultado) {
                        header('Location: /mensaje');
                    }
                }
            }
        }

        //Render a la vista
        $router->render('auth/crear', [
            'titulo' => 'Crear Cuenta en UpTask',
            'usuario' => $usuario,
            'alertas' => $alertas,
        ]);
    }

    public static function olvide(Router $router)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        }

        $router->render('auth/olvide', ['titulo' => 'Olvidé mi password']);
    }

    public static function reestablecer(Router $router)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        }

        $router->render('auth/reestablecer', ['titulo' => 'Reestablecer password']);
    }

    public static function mensaje(Router $router)
    {
        $router->render('auth/mensaje', ['titulo' => 'Cuenta creada exitosamente']);
    }

    public static function confirmar(Router $router)
    {

        $token = s($_GET['token']);

        if (!$token) header('Location: /');

        //Encontrar usuario con este token
        $usuario = Usuario::where('token', $token);

        if (empty($usuario)) {
            //Usuario con ese token no encontrado
            Usuario::setAlerta('error', 'Token no válido');
        } else {
            //Confirmar cuenta
            $usuario->confirmado = 1;
            $usuario->token = null;
            unset($usuario->password2);

            //Guardar en la BD
            $usuario->guardar();

            Usuario::setAlerta('exito', 'Cuenta confirmada correctamente');
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/confirmar', ['titulo' => 'Confirma tu cuenta UpTask', 'alertas' => $alertas]);
    }
}
