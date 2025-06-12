<?php
    include('mysqli_connect.php'); // Este ficheiro deve definir $pdo com PDO

    // SELECT
    $sql = "SELECT id, marca, modelo, preco, armazenamento, ram, sistema_operativo FROM telemoveis";
    $stmt = $pdo->query($sql);
    $telemoveis = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ADICIONAR
    if (isset($_POST['adicionar'])) {
        $marca = $_POST['marca'];
        $modelo = $_POST['modelo'];
        $preco = $_POST['preco'];
        $armazenamento = $_POST['armazenamento'];
        $ram = $_POST['ram'];
        $sistema_operativo = $_POST['sistema_operativo'];

        try {
            $sql = "INSERT INTO telemoveis (marca, modelo, preco, armazenamento, ram, sistema_operativo) 
                    VALUES (:marca, :modelo, :preco, :armazenamento, :ram, :sistema_operativo)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':marca' => $marca,
                ':modelo' => $modelo,
                ':preco' => $preco,
                ':armazenamento' => $armazenamento,
                ':ram' => $ram,
                ':sistema_operativo' => $sistema_operativo
            ]);
            header("Location: index.php");
            exit;
        } catch (PDOException $e) {
            echo "Erro ao adicionar: " . $e->getMessage();
        }
    }

    // APAGAR
    if (isset($_POST['apagar'])) {
        $id = $_POST['id'];
        try {
            $sql = "DELETE FROM telemoveis WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            header("Location: index.php");
            exit;
        } catch (PDOException $e) {
            echo "Erro ao apagar: " . $e->getMessage();
        }
    }

    // EDITAR
    if (isset($_POST['guardar_editar'])) {
        $id = $_POST['id'];
        $marca = $_POST['marca'];
        $modelo = $_POST['modelo'];
        $preco = $_POST['preco'];
        $armazenamento = $_POST['armazenamento'];
        $ram = $_POST['ram'];
        $sistema_operativo = $_POST['sistema_operativo'];

        try {
            $sql = "UPDATE telemoveis SET 
                        marca = :marca,
                        modelo = :modelo,
                        preco = :preco,
                        armazenamento = :armazenamento,
                        ram = :ram,
                        sistema_operativo = :sistema_operativo
                    WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':marca' => $marca,
                ':modelo' => $modelo,
                ':preco' => $preco,
                ':armazenamento' => $armazenamento,
                ':ram' => $ram,
                ':sistema_operativo' => $sistema_operativo,
                ':id' => $id
            ]);
            header("Location: index.php");
            exit;
        } catch (PDOException $e) {
            echo "Erro ao editar: " . $e->getMessage();
        }
    }
?>
