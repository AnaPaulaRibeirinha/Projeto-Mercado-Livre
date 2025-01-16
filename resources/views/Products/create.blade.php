<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Produto</title>
</head>

<body>
    <h1>Cadastrar Produto</h1>
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="name">Nome:</label>
        <input type="text" name="name" id="name" required><br>

        <label for="description">Descrição:</label>
        <textarea name="description" id="description" required></textarea><br>

        <label for="price">Preço:</label>
        <input type="number" name="price" id="price" step="0.01" required><br>

        <label for="quantity">Quantidade:</label>
        <input type="number" name="quantity" id="quantity" min="1" required><br>

        <label for="category_id">Categoria:</label>
        <input type="text" name="category_id" id="category_id" required><br>

        <label for="image">Imagem:</label>
        <input type="file" name="image" id="image"><br>

        <button type="submit">Cadastrar</button>
    </form>
</body>

</html>
