<?php
    include('mysqli_connect.php');
    include('db_queries.php');
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Catálogo de Telemóveis</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet"
          href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center mb-4">Catálogo de Telemóveis</h1>

    <!-- Botão Adicionar -->
    <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addModal">Adicionar Telemóvel</button>

    <!-- Tabela -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover text-center">
            <thead class="thead-light">
            <tr>
                <th>ID</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Preço (€)</th>
                <th>Armazenamento (GB)</th>
                <th>RAM (GB)</th>
                <th>Sistema Operativo</th>
                <th>Ações</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($telemoveis as $tel): ?>
                <tr>
                    <td><?= htmlspecialchars($tel['id']) ?></td>
                    <td><?= htmlspecialchars($tel['marca']) ?></td>
                    <td><?= htmlspecialchars($tel['modelo']) ?></td>
                    <td><?= htmlspecialchars($tel['preco']) ?></td>
                    <td><?= htmlspecialchars($tel['armazenamento']) ?></td>
                    <td><?= htmlspecialchars($tel['ram']) ?></td>
                    <td><?= htmlspecialchars($tel['sistema_operativo']) ?></td>
                    <td>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $tel['id'] ?>">
                            <button type="submit" name="apagar" class="btn btn-danger btn-sm">Apagar</button>
                        </form>
                        <button type="button" class="btn btn-warning btn-sm"
                                data-toggle="modal"
                                data-target="#editModal"
                                data-id="<?= $tel['id'] ?>"
                                data-marca="<?= htmlspecialchars($tel['marca']) ?>"
                                data-modelo="<?= htmlspecialchars($tel['modelo']) ?>"
                                data-preco="<?= $tel['preco'] ?>"
                                data-armazenamento="<?= $tel['armazenamento'] ?>"
                                data-ram="<?= $tel['ram'] ?>"
                                data-so="<?= htmlspecialchars($tel['sistema_operativo']) ?>">
                            Editar
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Adicionar -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="post" action="index.php">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Adicionar Telemóvel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Marca</label>
                        <input type="text" name="marca" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Modelo</label>
                        <input type="text" name="modelo" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Preço (€)</label>
                        <input type="number" step="0.01" name="preco" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Armazenamento (GB)</label>
                        <input type="number" name="armazenamento" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>RAM (GB)</label>
                        <input type="number" name="ram" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Sistema Operativo</label>
                        <input type="text" name="sistema_operativo" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="adicionar" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Editar -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="post" action="index.php">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Telemóvel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit-id">
                    <div class="form-group">
                        <label>Marca</label>
                        <input type="text" name="marca" id="edit-marca" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Modelo</label>
                        <input type="text" name="modelo" id="edit-modelo" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Preço (€)</label>
                        <input type="number" step="0.01" name="preco" id="edit-preco" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Armazenamento (GB)</label>
                        <input type="number" name="armazenamento" id="edit-armazenamento" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>RAM (GB)</label>
                        <input type="number" name="ram" id="edit-ram" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Sistema Operativo</label>
                        <input type="text" name="sistema_operativo" id="edit-so" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="guardar_editar" class="btn btn-primary">Guardar Alterações</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Scripts Bootstrap e JavaScript -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script
        src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $('#editModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        $('#edit-id').val(button.data('id'));
        $('#edit-marca').val(button.data('marca'));
        $('#edit-modelo').val(button.data('modelo'));
        $('#edit-preco').val(button.data('preco'));
        $('#edit-armazenamento').val(button.data('armazenamento'));
        $('#edit-ram').val(button.data('ram'));
        $('#edit-so').val(button.data('so'));
    });
</script>
</body>
</html>
