<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Editar Agendamento</title>
    </head>
    <body>
        <h1>Editar Agendamento</h1>

        <form action="<?= base_url('agendamentos/atualizar/'.$agendamentos['AGE_ID']) ?>" method="POST">
            <label>Data e Hora:</label><br>
            <input type="datetime-local" id="data_hora" name="data_hora" value="<?= date('Y-m-d\TH:i', strtotime($agendamentos['AGE_DATA_HORA'])) ?>" required>
            <br><br>

            <label>Motivo:</label><br>
            <input type="text" id="motivo" name="motivo" value="<?= $agendamentos['AGE_MOTIVO'] ?>" required>
            <br><br>

            <label>Status:</label><br>
            <input type="text" id="status" name="status" value="<?= $agendamentos['AGE_STATUS'] ?>" required>
            <br><br>

            <label>Cliente:</label><br>
                <select id="cliente" name="cliente" required>
                    <option value="">Selecione um cliente</option>
                    <?php foreach($clientes as $cliente): ?>
                        <option value="<?= $cliente['CLI_ID'] ?>" <?= ($cliente['CLI_ID'] == $agendamentos['FK_CLI_ID']) ? 'selected' : '' ?>>
                            <?= $cliente['CLI_NOME'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <br><br>

                <label>Veículo:</label><br>
                <select id="veiculo" name="veiculo" required>
                    <option value="">Selecione um veículo</option>
                    <?php foreach($veiculos as $veiculo): ?>
                        <option value="<?= $veiculo['VEI_ID'] ?>" <?= ($veiculo['VEI_ID'] == $agendamentos['FK_VEI_ID']) ? 'selected' : '' ?>>
                            <?= $veiculo['VEI_PLACA'] ?> - <?= $veiculo['VEI_MODELO'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <br><br>

            <input type="submit" id="editar_agendamento" name="editar_agendamento" value="Salvar Alterações">
        </form>
        <br>
        <a href="<?= base_url('agendamentos') ?>"><button>Voltar</button></a>
    </body>
</html>