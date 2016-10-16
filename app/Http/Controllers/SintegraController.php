<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use GuzzleHttp\Client;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Sintegra;

class SintegraController extends Controller
{
    /**
     * Tela incial do sistema, onde é exibida a listagem e a opção de fazer uma consulta de cnpj.
     */
    public function index() {
        return view('sintegra.index');
    }

    public function consultar(Request $request) {
        $validacao = $this->validarCnpj($request->input('cnpj'));
        if (!$validacao['status']) {
            return response()->json($validacao, 400);
        }

        $retornoConsulta = $this->consultarCnpj($request->input('cnpj'));
dd($retornoConsulta);
        dd($this->formatarRetornoConsulta($retornoConsulta));
    }

    /**
    * Faz a validação do cnpj enviado na requisição
    * @param $cnpj
    * @return array
    */
    private function validarCnpj($cnpj) {
        $validacao = Validator::make(
            ['num_cnpj' => $cnpj],
            ['num_cnpj' => 'required|min:18']
        );

        if ($validacao->fails()) {
            return [
                'status' => false,
                'error'  => implode(', ', $validacao->getMessageBag()->getMessages()['num_cnpj'])
            ];
        }

        return ['status' => true];
    }

    /**
     * Faz a requisição ao sistema da sintegra para consultar o cnpj
     * @param $cnpj
     * @return false caso cnpj não seja encontrado ou body em caso de sucesso
     */
    public function consultarCnpj($cnpj) {
        $client   = new Client();
        $resposta = $client->request('POST', Sintegra::ENDPOINT . 'resultado.php', [
            'form_params' => [
                'num_cnpj' => $cnpj,
                'botao'    => 'Consultar'
            ]
        ]);

        return $resposta->getBody()->getContents();
    }

    /**
     * Recebe o retorno da consulta e formata para retornar ao cliente
     * @return 
     */
    public function formatarRetornoConsulta($retornoConsulta) {
        if (stripos($body, 'class="resultado"') === false) {
            return false;
        }
        
        return $body;
    }
}