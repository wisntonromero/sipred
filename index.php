<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>SIPRED - Sistema de Inventario de Puntos de Red</title> <!-- Titulo prestaña -->
		<p style="text-align: center; overflow: hidden; ">
        <img style="width: 303px; height: 146px margin: -55px -216px -112px -140px;" src="images/jpg/logo-empresa.jpg" alt="Logo empresa"/>
        </p>
		<h1 align="center" > <strong>Sistema de Inventario de Puntos de Red</strong></h1> <!-- Titulo Nombre del software -->
        <h1 align="center" > <strong>SIPRED</strong></h1> <!-- Titulo Nombre del software -->
        <link rel="stylesheet" href="foundation-6.3.1/css/foundation.css"/>
    </head>


<div id="wrapper" style="position: relative;">
       
            <form action="acceso-php_validar_usuario.php" method="post">
               
                    <tbody>

                        <tr>
                            <td align="left" style="vertical-align: top;">
                                <div class="gwt-Label" style="height: auto; width: 100%;">Usuario:</div>
                            </td>
                        </tr>
                        <tr>
                            <td align="left" style="vertical-align: top;">
                                <input type="text" id="usuario" name="usuario" class="gwt-TextBox" required="required" style="height: auto; font-size:12px; font-weight:bold; width: 100%;" autofocus>
                            </td>
                        </tr>

                        <tr>
                            <td align="left" style="vertical-align: top;">
                                <div class="gwt-Label" style="height: auto; width: 100%;">Contraseña:</div>
                            </td>
                        </tr>
                        <tr>
                            <td align="left" style="vertical-align: top;">
                                <input type="password" id="contrasena" name="contrasena" required="required" class="gwt-PasswordTextBox" style="height: auto; font-size:12px; font-weight:bold; width: 100%;">
                            </td>
                        </tr>

                        <tr>
                            <td align="left" style="vertical-align: top;">
                                <input type="hidden" id="ubicacion_foto" name="ubicacion_foto" class="gwt-TextBox" required="required" style="height: auto; font-size:12px; font-weight:bold; width: 100%;">
                            </td>
                        </tr>

                        <tr>
                            <td align="left" style="vertical-align: top;">
                                <table cellspacing="0" cellpadding="0" style="width: 100%;">
                                    <tbody>
                                        <tr>
                                            <td align="left" style="vertical-align: top;">
                                              <!--  <img class="gwt-Image" title="Loading" style="display: none;" alt="Loading" src="assets/square_circles.gif"> -->
                                            </td>
                                            <td align="right" style="vertical-align: top;">
                                              <!--  <button type="button" class="loginButton" style="height: 25px;">&gt;&gt;&nbsp;&nbsp;&nbsp;Enviar</button>  -->
                                                <td colspan="2"><input name="iniciar" class="loginButton" type="submit" value="Iniciar Sesión"/></td>
                                                 <td colspan="2"><input type="text" value="Iniciar Sesión"/></td>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>

                    </tbody>
            
            </form>





      
    </div class="row">

    <div>
        

     <?php

                    ########## Google Settings.. Client ID, Client Secret #############
                    $google_client_id       = '478080427954-7ba0a4qr3pel4a9jfdgsoommevv6dfuk.apps.googleusercontent.com';
                    $google_client_secret   = 'Gtn5M1MkUgVx0kQMh7leKUaF';
                    $google_redirect_url    = 'http://localhost:8888/google-login-master/index.php';
                    //$google_developer_key     = 'AIzaSyCjLXeXCsoPwdJ7n3Rr619VVxE6E91mTUk';

                    ########## MySql details (Replace with yours) #############
                    $db_username = "root"; //Database Username
                    $db_password = "root"; //Database Password
                    $hostname = "localhost"; //Mysql Hostname
                    $db_name = 'sipred'; //Database Name
                    ###################################################################

                    //include google api files
                    require_once 'google-login/src/Google_Client.php';
                    require_once 'google-login/src/contrib/Google_Oauth2Service.php';
                    //require 'phpmailer/PHPMailerAutoload.php';
                    //header('Content-Type: application/json');


                    //start session
                    session_start();

                    $gClient = new Google_Client();
                    $gClient->setApplicationName('Login');
                    $gClient->setClientId($google_client_id);
                    $gClient->setClientSecret($google_client_secret);
                    $gClient->setRedirectUri($google_redirect_url);
                    $gClient->setDeveloperKey($google_developer_key);

                    $google_oauthV2 = new Google_Oauth2Service($gClient);

                    //If user wish to log out, we just unset Session variable
                    if (isset($_REQUEST['reset'])) 
                    {
                      unset($_SESSION['token']);
                      $gClient->revokeToken();
                      header('Location: ' . filter_var($google_redirect_url, FILTER_SANITIZE_URL));
                    }

                    //Redirect user to google authentication page for code, if code is empty.
                    //Code is required to aquire Access Token from google
                    //Once we have access token, assign token to session variable
                    //and we can redirect user back to page and login.
                    if (isset($_GET['code'])) 
                    { 
                        $gClient->authenticate($_GET['code']);
                        $_SESSION['token'] = $gClient->getAccessToken();
                        header('Location: ' . filter_var($google_redirect_url, FILTER_SANITIZE_URL));
                        return;
                    }


                    if (isset($_SESSION['token'])) 
                    { 
                            $gClient->setAccessToken($_SESSION['token']);
                    }


                    if ($gClient->getAccessToken()) 
                    {
                          //Get user details if user is logged in
                          $user                 = $google_oauthV2->userinfo->get();
                          $user_id              = $user['id'];
                          $user_name            = filter_var($user['name'], FILTER_SANITIZE_SPECIAL_CHARS);
                          $password             = filter_var($user['password'], FILTER_SANITIZE_SPECIAL_CHARS);
                          $email                = filter_var($user['email'], FILTER_SANITIZE_EMAIL);
                          $profile_url          = filter_var($user['link'], FILTER_VALIDATE_URL);
                          $profile_image_url    = filter_var($user['picture'], FILTER_VALIDATE_URL);
                          $personMarkup         = "$email<div><img src='$profile_image_url?sz=50'></div>";
                          $_SESSION['token']    = $gClient->getAccessToken();
                    }
                    else 
                    {
                        //get google login url
                        $authUrl = $gClient->createAuthUrl();
                    }
                    echo '<body>';
                    /*echo '<h1>Login con Google</h1>';*/

                    if(isset($authUrl)) //user is not logged in, show login button
                    {
                        echo '<a class="login" href="'.$authUrl.'"><img src="google-login/images/google-login-button.png" /></a>';
                    } 
                    else // user logged in 
                    {
                       /* connect to mysql */
                        $connecDB = mysql_connect($hostname, $db_username, $db_password)or die("Unable to connect to MySQL");
                        mysql_select_db($db_name,$connecDB);
                        
                        //compare user id in our database
                        $result = mysql_query("SELECT COUNT(google_id) FROM google_users WHERE google_id=$user_id");
                        if($result === false) { 
                            die(mysql_error()); //result is false show db error and exit.
                        }
                        
                        $UserCount = mysql_fetch_array($result);
                     
                        if($UserCount[0]) //user id exist in database
                        {
                            echo 'Welcome back '.$user_name.'!';
                            echo '<pre>'; 
                            echo 'E-mail:  '.$email.'!';
                            
                            //$ubicacion_foto = $row["ubicacion_foto"];
                            //$correo = $row["correo"];
                            //$ext_tel = $row["ext_tel"];
                            //$contrasena = $row["contrasena"];

                            //Almacenamos el nombre de usuario en una variable de sesión usuario
                            $_SESSION['usuario']  = $usuario; 
                            $_SESSION['nombre']   = $user_name; 
                            $_SESSION['correo']   = $email;
                            $_SESSION['ext_tel']  = $ext_tel;
                            $_SESSION['contrasena']  = $contrasena;  
                            $_SESSION['ubicacion_foto']     = $ubicacion_foto; 

                            



                            //Redireccionamos a la pagina: inicio.php
                            header("Location: inicio.php");  



                        }else{ //user is new
                            echo 'Hi '.$user_name.', Thanks for Registering!';
                            @mysql_query("INSERT INTO google_users (google_id, google_name, google_email, google_link, google_picture_link) VALUES ($user_id, '$user_name','$email','$profile_url','$profile_image_url')");
                        }

                        
                        echo '<br /><a href="'.$profile_url.'" target="_blank"><img src="'.$profile_image_url.'?sz=50" /></a>';
                        echo '<br /><a class="logout" href="?reset=1">Logout</a>';
                        
                        //list all user details
                        echo '<pre>'; 
                        print_r($user);
                        echo '</pre>';  

                        echo '<pre>'; 
                        print_r($email);
                        echo '</pre>';  

                        ECHO $_SESSION['token']     = $gClient->getAccessToken();
                    }
                     
                    echo '</body></html>';
                    ?>

    </div>
