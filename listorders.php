<?php
require $root . 'include/db_credentials.php';

// list all orders from ordersummary
?>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Order List</title>
    <style type="text/css">
        * {
            box-sizign: border-box;
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        table {
            display: flex;
            /* width: 100vw; */
            align-content: space-around;
            flex-direction: column;
        }

        tbody,
        thead,
        tfoot {
            display: block;
            color: black;
            background-color: white;
            width: 100%;
        }

        tr {
            display: flex;
            /* justify-content: center; */
            /* align-items: center; */
        }

        th,
        td {
            display: inline-flex;
            textword-wrap: wrap;
            width: 10%;
            /* overflow: auto; */
        }

        body>table>thead> tr :nth-child(5),
        body>table>tbody> tr :nth-child(5) {
            width: 60%;
        }
    </style>
</head>

<body>

    <?php
$db = new DB();
$sql = "SELECT * FROM ordersummary";

$result = sqlsrv_query($db->conn, $sql);
$total = 0;
echo '<table class="noborder" width="100%">';
echo '<thead>
        <tr>
            <th>Customer Id</th>
            <th>Order Id</th>
            <th>Order Date</th>
            <th>Order Total</th>
            <th>Orders</th>
        </tr>
    </thead>
    <tbody>';
while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
    $id = $row['orderId'];
    $date = $row['orderDate'];
    $amount = $row['totalAmount'];
    $total += $amount;
    // echo '<tr><td class='
    echo '<tr>';
    echo '<td>' . $row['customerId'] . '</td>';
    echo '<td>' . $id . '</td>';
    echo '<td>' . $date->format('M y, d') . '</td>';
    echo '<td>$ ' . number_format($amount, 2) . '</td>';
    echo '<td><table width="100%"><thead>
    <tr>
    <th>Id</th>
    <th style="width: 60%">Name</th>
    <th>Qty</th>
    <th>Price</th>
    <th style="width: 10%">Subtotal</th>
    </tr>
    </thead><tbody>';
    $sql = 'SELECT * FROM orderproduct WHERE orderId = ' . $id;
    $subtotal = 0;
    $result2 = sqlsrv_query($db->conn, $sql);
    while ($row2 = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC)) {
        $subtotal += $row2['price'] * $row2['quantity'];
        $product = Product::GetProduct($row2['productId']);
        echo '<tr>
        <td>' . $row2['productId'] . '</td>';
        echo '<td style="width: 60%">' . $product->productName . '</td>';
        echo '<td>' . $row2['quantity'] . '</td>';
        echo '<td>$ ' . number_format($row2['price'], 2) . '</td>
        <td style="width: 10%">$ ' . number_format($subtotal, 2) . '</td>
        </tr>';
    }
    echo '</tbody>
    <tfoot>
        <tr>
            <td colspan="9"></td>
            <td>Subtotal</td>
            <td>$ ' . number_format($subtotal, 2) . '</td>
        </tr>
    </tfoot>
    </table></td>';
    echo '</tr>';
}

echo '</tbody>
<tfoot>
<tr>
<td colspan="9"></td>
<td>Total</td>
<td>$ ' . number_format($total, 2) . '</td>
</tr>
</tfoot>
</table>';
?>
</body>

</html>