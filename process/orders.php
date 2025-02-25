<?php

    include_once("conn.php");
   
    $method = $_SERVER["REQUEST_METHOD"];

    if($method === "GET") {

        $pedidosQuery = $conn->query("SELECT * FROM pedidos");

        $pedidos = $pedidosQuery->fetchAll();

        $pizzas = [];

        // Montando pizza
        foreach($pedidos as $pedido) {
            
            $pizza = [];
            // Definindo um array para a pizza
            $pizza["id"] = $pedido["pizza_id"];
            // Resgatando a pizza
            $pizzaQuery = $conn->prepare("SELECT * FROM pizzas WHERE id = :pizza_id");

            $pizzaQuery->bindParam(":pizza_id", $pizza["id"]);

            $pizzaQuery->execute();

            $pizzaData = $pizzaQuery->fetch(PDO::FETCH_ASSOC);

            // Resgatando a borda da pizza

            $bordaQuery = $conn -> prepare("SELECT * FROM bordas WHERE id = :borda_id");

            $bordaQuery->bindParam(":borda_id", $pizzaData["borda_id"]);

            $bordaQuery->execute();

            $borda = $bordaQuery->fetch(PDO::FETCH_ASSOC);

            $pizza["borda"] = $borda["tipo"];

            // Resgatando massas da pizza

            $massaQuery = $conn  -> prepare("SELECT * FROM massas WHERE id = :massa_id");

            $massaQuery->bindParam(":massa_id", $pizzaData["massa_id"]);

            $massaQuery->execute();

            $massa = $massaQuery->fetch(PDO::FETCH_ASSOC);

            $pizza["massa"] = $massa["tipo"];

            // Resgatando os sabores da pizza

            $saboresQuery = $conn -> prepare("SELECT * FROM pizza_sabor WHERE pizza_id = :pizza_id");

            $saboresQuery->bindParam(":pizza_id", $pizza["id"]);

            $saboresQuery->execute();

            $sabores = $saboresQuery->fetchAll(PDO::FETCH_ASSOC);

            // Resgatando o nome dos sabores

            $saboresDaPizza = [];

            $saborQuery = $conn->prepare("SELECT * FROM sabores WHERE id = :sabor_id");

            foreach ($sabores as $sabor) {

                $saborQuery->bindParam(":sabor_id", $sabor["sabor_id"]);

                $saborQuery->execute();

                $saborPizza = $saborQuery->fetch(PDO::FETCH_ASSOC);

                array_push($saboresDaPizza, $saborPizza["nome"]);
            }

            $pizza["sabores"] = $saboresDaPizza;

            // Adicionar o status do pedido

            $pizza["status"] = $pedido["status_id"];

            // Adicionar o array da pizza ao array de pedidos

            array_push($pizzas, $pizza);
        }

        print_r($pizzas);

         // Resgatando os status
         $statusQuery = $conn->query("SELECT * FROM status");

         $status = $statusQuery->fetchAll();

    } else if($method === "POST") {

    }

?>