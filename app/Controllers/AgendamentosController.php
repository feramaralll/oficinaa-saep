<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\AgendamentosModel;
use App\Models\VeiculosModel;
use App\Models\ClientesModel;

// Vincula o Agendamento ao Veículo (FK_VEI_ID) com seu respectivo Cliente (FK_CLI_ID)
class AgendamentosController extends BaseController
{
    // Exibe a listagem de agendamentos
    public function index()
    {
        // Instancia o Model de agendamento
        $model = new AgendamentosModel();

        // Verifica se o formulário de pesquisa foi enviado
        if ($this->request->getPost('botao_pesquisar')) {

            // Recupera o termo digitado
            $pesquisar = $this->request->getPost('pesquisar');

            // Busca os agendamentos juntamente com
            // informações do veículo e do cliente
            $dados['agendamentos'] = $model
                ->select(
                    'AGENDAMENTOS.*,
                    VEICULOS.VEI_PLACA,
                    VEICULOS.VEI_MODELO,
                    CLIENTES.CLI_NOME'
                )

                // Relaciona o agendamento ao veículo
                ->join(
                    'VEICULOS',
                    'VEICULOS.VEI_ID = AGENDAMENTOS.FK_VEI_ID'
                )

                // Relaciona o agendamento ao cliente
                ->join(
                    'CLIENTES',
                    'CLIENTES.CLI_ID = AGENDAMENTOS.FK_CLI_ID'
                )

                // Agrupa as condições utilizadas na pesquisa
                ->groupStart()

                    // Pesquisa pela placa ou modelo do veículo
                    ->like('VEICULOS.VEI_PLACA', $pesquisar)
                    ->orLike('VEICULOS.VEI_MODELO', $pesquisar)

                    // Pesquisa pelo nome do cliente
                    ->orLike('CLIENTES.CLI_NOME', $pesquisar)

                    // Pesquisa pelo motivo
                    ->orLike('AGE_MOTIVO', $pesquisar)

                    // Pesquisa pelo status
                    ->orLike('AGE_STATUS', $pesquisar)

                ->groupEnd()

                // Ordena os agendamentos pela data e hora
                ->orderBy('AGE_DATA_HORA', 'ASC')

                // Executa a consulta
                ->findAll();
        }
        else {

            // Caso nenhuma pesquisa tenha sido realizada,
            // busca todos os agendamentos
            $dados['agendamentos'] = $model
                ->select(
                    'AGENDAMENTOS.*,
                    VEICULOS.VEI_PLACA,
                    VEICULOS.VEI_MODELO,
                    CLIENTES.CLI_NOME'
                )

                // Relaciona o veículo ao agendamento
                ->join(
                    'VEICULOS',
                    'VEICULOS.VEI_ID = AGENDAMENTOS.FK_VEI_ID'
                )

                // Relaciona o cliente ao agendamento
                ->join(
                    'CLIENTES',
                    'CLIENTES.CLI_ID = AGENDAMENTOS.FK_CLI_ID'
                )

                // Ordena pela data e hora do agendamento
                ->orderBy('AGE_DATA_HORA', 'ASC')

                // Executa a consulta
                ->findAll();
        }

        // Carrega a View com os agendamentos encontrados
        return view('sistema/agendamentos/index', $dados);
    }


    // Exibe o formulário para cadastrar um novo agendamento
    public function novo()
    {
        // Instancia o Model de veículo
        $veiculoModel = new VeiculosModel();

        // Instancia o Model de cliente
        $clienteModel = new ClientesModel();

        // Busca todos os veículos para preencher o SELECT
        $dados['veiculos'] = $veiculoModel->findAll();

        // Busca todos os clientes para preencher o SELECT
        $dados['clientes'] = $clienteModel->findAll();

        // Carrega o formulário
        return view(
            'sistema/agendamentos/novo_agendamento',
            $dados
        );
    }


    // Insere um novo agendamento
    public function inserir()
    {
        // Instancia o Model
        $model = new AgendamentosModel();

        // Recupera os dados enviados pelo formulário
        $dados = [
            'AGE_DATA_HORA' => $this->request->getPost('data_hora'),
            'AGE_MOTIVO'    => $this->request->getPost('motivo'),
            'AGE_STATUS'    => $this->request->getPost('status'),

            // Veículo escolhido no formulário
            'FK_VEI_ID'     => $this->request->getPost('veiculo'),

            // Cliente escolhido no formulário
            'FK_CLI_ID'     => $this->request->getPost('cliente')
        ];

        // Insere o agendamento no banco
        $model->insert($dados);

        // Redireciona para a listagem
        return redirect()
            ->to(base_url('agendamentos'))
            ->with('success', 'Agendamento cadastrado com sucesso!');
    }


    // Exibe o formulário de edição
    public function editar($id)
    {
        // Instancia os três Models necessários
        $agendamentosModel = new AgendamentosModel();
        $veiculoModel      = new VeiculosModel();
        $clienteModel      = new ClientesModel();

        // Busca o agendamento pelo ID
        $dados['agendamentos'] = $agendamentosModel->find($id);

        // Busca os veículos para preencher o SELECT
        $dados['veiculos'] = $veiculoModel->findAll();

        // Busca os clientes para preencher o SELECT
        $dados['clientes'] = $clienteModel->findAll();

        // Carrega a View de edição
        return view(
            'sistema/agendamentos/editar_agendamento',
            $dados
        );
    }


    // Atualiza um agendamento
    public function atualizar($id)
    {
        // Instancia o Model
        $model = new AgendamentosModel();

        // Recupera os novos valores enviados pelo formulário
        $dados = [
            'AGE_DATA_HORA' => $this->request->getPost('data_hora'),
            'AGE_MOTIVO'    => $this->request->getPost('motivo'),
            'AGE_STATUS'    => $this->request->getPost('status'),
            'FK_VEI_ID'     => $this->request->getPost('veiculo'),
            'FK_CLI_ID'     => $this->request->getPost('cliente')
        ];

        // Atualiza o agendamento no banco
        $model->update($id, $dados);

        // Redireciona para a listagem
        return redirect()
            ->to(base_url('agendamentos'))
            ->with('success', 'Agendamento atualizado com sucesso!');
    }


    // Exclui um agendamento
    public function excluir($id)
    {
        // Instancia o Model
        $model = new AgendamentosModel();

        // Exclui o agendamento pelo ID
        $model->delete($id);

        // Redireciona para a listagem
        return redirect()
            ->to(base_url('agendamentos'))
            ->with('success', 'Agendamento excluído com sucesso!');
    }
}