<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\CadastroQuiz;

use App\Models\Quiz;
use App\Models\Questao;
use App\Models\RespostaQuestao;
use App\Models\QuestaoQuiz;
use App\Models\PontoTuristico;

class QuizController extends Controller
{
    public function index(){
        $quiz = Quiz::with(['pontoTuristico'])->paginate(10);
        
        return view('admin/listarQuiz')->with([
            'quiz' => $quiz
        ]);
    }
    
    public function create()
    {
        return view('admin.cadastrarQuiz')->with([
            'ponto' => PontoTuristico::getPontosTuristicos()
        ]);
    
    }
    
    public function store(CadastroQuiz $request)
    {
        $quiz           =   Quiz::create(['id_ponto_turistico' => $request->input('id_ponto_turistico'), 'qt_questao' => 5])->id_quiz;
        $questao        =   Questao::create($request->only('nm_questao'))->id_questao;
        $questaoQuiz    =   QuestaoQuiz::create(['id_quiz' => $quiz, 'id_questao' => $questao]);
        
        RespostaQuestao::adicionarRespostaQuestao($request->only(['ds_resposta_questao', 'ic_resposta_correta']), $questao);
        
        return redirect()->back()->withMensagem([
            'text'      =>  'Quiz cadastrado com sucesso.',
            'class'     =>  'success'
        ]);
    }
    
    public function edit(Quiz $quiz) 
    {
        dd($quiz->questaoQuiz);
        
        return view('admin.editarQuiz')->with([
            'ponto'         =>  PontoTuristico::getPontosTuristicos(),
            'respostas'     =>  $quiz->questaoQuiz->questao->respostasQuestao,
            'quiz'          =>  $quiz,
        ]);
    }
    
    public function update(Request $request)
    {
        dd($request->all());
    }
}
