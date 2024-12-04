<?php
use PHPMailer\PHPMailer\PHPMailer;
function isNull($nombre, $user, $pass, $pass_con, $email) {
    if (strlen(trim($nombre)) < 1 || strlen(trim($user)) < 1 || strlen(trim($email)) < 1) {
        return true;
    } else {
        return false;
    }
}

function isEmail($email) {
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        return false;
    }
}

function validaPassword($var1, $var2) {
    if (strcmp($var1, $var2) !== 0) {
        return false;
    } else {
        return true;
    }
}

function minMax($min, $max, $valor) {
    if (strlen(trim($valor)) < $min) {
        return true;
    } else if (strlen(trim($valor)) > $max) {
        return true;
    } else {
        return false;
    }
}

function usuarioExiste($usuario) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE usuario = ? LIMIT 1");
    $stmt->execute([$usuario]);
    $num = $stmt->rowCount();

    return $num > 0;
}

function emailExiste($email) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE correo = ? LIMIT 1");
    $stmt->execute([$email]);
    $num = $stmt->rowCount();

    return $num > 0;
}

function hashPassword($password) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    return $hash;
}

function generateToken() {
    $gen = md5(uniqid(mt_rand(), false));
    return $gen;
}

function registraUsuario($usuario, $pass_hash, $nombre, $email, $activo, $token, $tipo_usuario) {
    global $pdo;

    $stmt = $pdo->prepare("INSERT INTO usuarios (usuario, password, nombre, correo, activacion, token, id_tipo) VALUES(?,?,?,?,?,?,?)");
    $result = $stmt->execute([$usuario, $pass_hash, $nombre, $email, $activo, $token, $tipo_usuario]);

    return $result ? $pdo->lastInsertId() : 0;
}

function enviarEmail($email, $nombre, $asunto, $cuerpo){
    require("PHPMailer-master/src/PHPMailer.php");
    require("PHPMailer-master/src/Exception.php");
    require("PHPMailer-master/src/SMTP.php");
    // $mail = new PHPMailer();
    $mail = new PHPMailer(); 
    // $mail->SMTPDebug = 2; Usar para verificar errores
    $mail->IsSMTP();
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = "tls";

    // $mail->CharSet="UTF-8";
    // $mail->Host = 'smtp.gmail.com';
    // $mail->SMTPSecure = 'tipo de seguridad';
    $mail->Host = "smtp.gmail.com";
    $mail->Port = 587;

    $mail->Username = "woodvibe7@gmail.com";
    $mail->Password = "kbqp oucr ofbt wplv";

    // $mail->SetFrom('miemail@dominio.com', 'Sistema con PHP');
    // $mail->AddAddress($email, $nombre);
    $mail->SetFrom('woodvibe7@gmail.com', 'WoodVibe');
    $mail->AddAddress($email, $nombre);

    $mail->Subject = $asunto;
    $mail->Body = $cuerpo;
    $mail->IsHTML(true);

    if($mail->Send())
    return true;
    else
    return false;
}

function resultBlock($errors) {
    if (count($errors) > 0) {
        echo "<div id='error' class='alert alert-danger' role='alert'>
            <a href='#' onclick=\"showHide('error');\">[X]</a><ul>";
        foreach ($errors as $error) {
            echo "<li>".$error."</li>";
        }
        echo "</ul>";
        echo "</div>";
    }
}

function validaIdToken($id, $token) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT activacion FROM usuarios WHERE id = ? AND token = ? LIMIT 1");
    $stmt->execute([$id, $token]);
    $rows = $stmt->rowCount();

    if ($rows > 0) {
        $activacion = $stmt->fetchColumn();

        if ($activacion == 1) {
            $msg = "La cuenta ya se activó anteriormente.";
        } else {
            if (activarUsuario($id)) {
                $msg = 'Cuenta activada.';
            } else {
                $msg = 'Error al activar cuenta.';
            }
        }
    } else {
        $msg = 'No existe el registro para activar.';
    }

    return $msg;
}

function activarUsuario($id) {
    global $pdo;

    $stmt = $pdo->prepare("UPDATE usuarios SET activacion=1 WHERE id = ?");
    return $stmt->execute([$id]);
}

function isNullLogin($usuario, $password) {
    return (strlen(trim($usuario)) < 1 || strlen(trim($password)) < 1);
}

function login_ES($usuario, $password) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT id, id_tipo, password, nombre FROM usuarios WHERE usuario = ? or correo = ? LIMIT 1");
    $stmt->bindValue(1, $usuario);
    $stmt->bindValue(2, $usuario);
    $stmt->execute([$usuario, $usuario]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (isActivo($usuario)) {
            if (password_verify($password, $user['password'])) {
                if ($user['id_tipo'] == "1") {
                    lastSession($user['id']);
                    $_SESSION['id_usuario'] = $user['id'];
                    $_SESSION['tipo_usuario'] = $user['id_tipo'];
                    $_SESSION['nombre'] = $user['nombre'];
                    header("location: DB.php");
                } elseif ($user['id_tipo'] == "2") {
                    lastSession($user['id']);
                    $_SESSION['id_usuario'] = $user['id'];
                    $_SESSION['tipo_usuario'] = $user['id_tipo'];
                    $_SESSION['nombre'] = $user['nombre'];
                    header("location: ES_view_user.php");
                }
            } else {
                $errors = "La contraseña no coincide";
            }
        } else {
            $errors = 'El usuario no está activo';
        }
    } else {
        $errors = 'El nombre de usuario no existe';
    }
    return $errors;
}


function login($usuario, $password) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT id, id_tipo, password, nombre FROM usuarios WHERE usuario = ? or correo = ? LIMIT 1");
    $stmt->bindValue(1, $usuario);
    $stmt->bindValue(2, $usuario);
    $stmt->execute([$usuario, $usuario]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (isActivo($usuario)) {
            if (password_verify($password, $user['password'])) {
                if ($user['id_tipo'] == "1") {
                    lastSession($user['id']);
                    $_SESSION['id_usuario'] = $user['id'];
                    $_SESSION['tipo_usuario'] = $user['id_tipo'];
                    $_SESSION['nombre'] = $user['nombre'];
                    header("location: DB.php");
                } elseif ($user['id_tipo'] == "2") {
                    lastSession($user['id']);
                    $_SESSION['id_usuario'] = $user['id'];
                    $_SESSION['tipo_usuario'] = $user['id_tipo'];
                    $_SESSION['nombre'] = $user['nombre'];
                    header("location: EN_view_user.php");
                }
            } else {
                $errors = "La contraseña no coincide";
            }
        } else {
            $errors = 'El usuario no está activo';
        }
    } else {
        $errors = 'El nombre de usuario no existe';
    }
    return $errors;
}

function lastSession($id) {
    global $pdo;

    $stmt = $pdo->prepare("UPDATE usuarios SET last_session=NOW(), token_password='', password_request=1 WHERE id= ?");
    $stmt->execute([$id]);
}

function isActivo($usuario) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT activacion FROM usuarios WHERE usuario = ? or correo = ? LIMIT 1");
    $stmt->execute([$usuario, $usuario]);
    return $stmt->fetchColumn() == 1;
}

function getValor($campo, $campoWhere, $valor) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT $campo FROM usuarios WHERE $campoWhere = ? LIMIT 1");
    $stmt->execute([$valor]);
    return $stmt->fetchColumn();
}

function generateTokenPass($user_id) {
    global $pdo;
    
    $token = generateToken();
    $stmt = $pdo->prepare("UPDATE usuarios SET token_password = ?, password_request = 1 WHERE id = ?");
    $stmt->execute([$token, $user_id]);
    
    return $token;
}

function verificaTokenPass($user_id, $token) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT activacion FROM usuarios WHERE id = ? AND token_password = ? AND password_request = 1 LIMIT 1");
    $stmt->execute([$user_id, $token]);
    return $stmt->fetchColumn() == 1;
}

function cambiaPassword($password, $user_id, $token) {
    global $pdo;

    $stmt = $pdo->prepare("UPDATE usuarios SET password = ?, token_password = '', password_request = 0 WHERE id = ? AND token_password = ?");
    return $stmt->execute([$password, $user_id, $token]);
}

class Cart {
    protected $cart_contents = array();

    public function __construct() {
        $this->cart_contents = !empty($_SESSION['cart_contents']) ? $_SESSION['cart_contents'] : array('cart_total' => 0, 'total_items' => 0);
    }

    public function contents() {
        $cart = array_reverse($this->cart_contents);
        unset($cart['total_items'], $cart['cart_total']);
        return $cart;
    }

    public function get_item($row_id) {
        return isset($this->cart_contents[$row_id]) ? $this->cart_contents[$row_id] : FALSE;
    }

    public function total_items() {
        return $this->cart_contents['total_items'];
    }

    public function total() {
        return $this->cart_contents['cart_total'];
    }

    public function insert($item = array()) {
        if (!is_array($item) OR count($item) === 0) {
            return FALSE;
        } else {
            if (!isset($item['id'], $item['name'], $item['price'], $item['qty'])) {
                return FALSE;
            } else {
                $item['qty'] = (float) $item['qty'];
                if ($item['qty'] == 0) {
                    return FALSE;
                }
                $item['price'] = (float) $item['price'];
                $rowid = md5($item['id']);
                $old_qty = isset($this->cart_contents[$rowid]['qty']) ? (int) $this->cart_contents[$rowid]['qty'] : 0;
                $item['rowid'] = $rowid;
                $item['qty'] += $old_qty;
                $this->cart_contents[$rowid] = $item;

                return $this->save_cart() ? $rowid : TRUE;
            }
        }
    }

    public function update($item = array()) {
        if (!is_array($item) OR count($item) === 0) {
            return FALSE;
        } else {
            if (!isset($item['rowid'], $this->cart_contents[$item['rowid']])) {
                return FALSE;
            } else {
                if (isset($item['qty'])) {
                    $item['qty'] = (float) $item['qty'];
                    if ($item['qty'] == 0) {
                        unset($this->cart_contents[$item['rowid']]);
                        return TRUE;
                    }
                }
                $keys = array_intersect(array_keys($this->cart_contents[$item['rowid']]), array_keys($item));
                if (isset($item['price'])) {
                    $item['price'] = (float) $item['price'];
                }
                foreach (array_diff($keys, array('id', 'name')) as $key) {
                    $this->cart_contents[$item['rowid']][$key] = $item[$key];
                }
                return $this->save_cart();
            }
        }
    }

    protected function save_cart() {
        $this->cart_contents['total_items'] = $this->cart_contents['cart_total'] = 0;
        foreach ($this->cart_contents as $key => $val) {
            if (!is_array($val) OR !isset($val['price'], $val['qty'])) {
                continue;
            }
            $this->cart_contents['cart_total'] += ($val['price'] * $val['qty']);
            $this->cart_contents['total_items'] += $val['qty'];
            $this->cart_contents[$key]['subtotal'] = ($this->cart_contents[$key]['price'] * $this->cart_contents[$key]['qty']);
        }
        if (count($this->cart_contents) <= 2) {
            unset($_SESSION['cart_contents']);
            return FALSE;
        } else {
            $_SESSION['cart_contents'] = $this->cart_contents;
            return TRUE;
        }
    }

    public function remove($row_id) {
        unset($this->cart_contents[$row_id]);
        return $this->save_cart();
    }

    public function destroy() {
        $this->cart_contents = array('cart_total' => 0, 'total_items' => 0);
        unset($_SESSION['cart_contents']);
    }
}
?>
