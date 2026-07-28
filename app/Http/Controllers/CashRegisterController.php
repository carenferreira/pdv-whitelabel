<?php

namespace App\Http\Controllers;

use App\Models\CashRegister;
use App\Enums\CashRegisterStatus;
use App\Enums\CashFlowType;
use App\Enums\MovementType;
use App\Enums\PaymentMethod;
use App\Models\CashFlow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CashRegisterController extends Controller
{
    /**
     * Mostra o caixa atual, ou formulário de abertura se fechado
     */
    public function index(){
        $caixaAberto = CashRegister::where('status', CashRegisterStatus::Open)->first();
        if(! $caixaAberto){
            return view('caixa.index',[
                'caixaAberto'=>null,
                'movimentacoes'=>collect(),
            ]);
        }

        $movimentacoes = CashFlow::where('cash_register_id',$caixaAberto->id)->orderBy('created_at','desc')->get();

        $saldoEntradas = $movimentacoes->where('type', CashFlowType::Entry->value)->sum('value');
        $saldoSaidas = $movimentacoes->where('type', CashFlowType::Withdrawal->value)->sum('value');
        $saldoParcial = $saldoEntradas - $saldoSaidas;

        return view('caixa.index', compact('caixaAberto', 'movimentacoes', 'saldoEntradas', 'saldoSaidas', 'saldoParcial'));
    }

    /**
     * Abre um novo caixa
     */
    public function abrir(Request $request)
    {
        $caixaAberto = CashRegister::where('status', CashRegisterStatus::Open)->first();

        //para o MVP só pode existir um caixa aberto
        if ($caixaAberto) {
            return redirect()->route('caixa.index')
                ->with('error', 'Já existe um caixa aberto. Feche-o antes de abrir outro.');
        }

        $validated = $request->validate([
            'opening_value' => 'required|integer|min:0',
            'observations' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($validated) {
            $caixa = CashRegister::create([
                'user_id' => Auth::id(),
                'opening_value' => $validated['opening_value'],
                'opening_date' => now(),
                'status' => CashRegisterStatus::Open,
                'observations' => $validated['observations'] ?? null,
            ]);

            CashFlow::create([
                'cash_register_id' => $caixa->id,
                'user_id' => Auth::id(),
                'type' => CashFlowType::Entry,
                'payment_method' => PaymentMethod::Cash,
                'movement_type' => MovementType::Opening,
                'value' => $validated['opening_value'],
                'description' => 'Abertura de caixa',
                'source_type' => 'cash_register',
                'source_id' => $caixa->id,
            ]);
        });

        return redirect()->route('caixa.index')
            ->with('success', 'Caixa aberto com sucesso.');
    }

    /**
     * Cria refistro de sangria
     */
     public function sangria(Request $request)
    {
        //dd('Chegou no método sangria', $request->all());
        $caixa = CashRegister::where('status', CashRegisterStatus::Open)->first();

        if (! $caixa) {
            return redirect()->route('caixa.index')
                ->with('error', 'Nenhum caixa aberto.');
        }

        $validated = $request->validate([
            'value' => 'required|integer|min:1',
            'description' => 'required|string|max:500',
        ]);

        // Verifica se há saldo suficiente
        $saldoEntradas = CashFlow::where('cash_register_id', $caixa->id)
            ->where('type', CashFlowType::Entry->value)
            ->sum('value');
        $saldoSaidas = CashFlow::where('cash_register_id', $caixa->id)
            ->where('type', CashFlowType::Withdrawal->value)
            ->sum('value');
        $saldoAtual = $saldoEntradas - $saldoSaidas;

        if ($validated['value'] > $saldoAtual) {
            return redirect()->back()
                ->with('error', 'Saldo insuficiente no caixa para essa sangria.')
                ->withInput();
        }

        CashFlow::create([
            'cash_register_id' => $caixa->id,
            'user_id' => Auth::id(),
            'type' => CashFlowType::Withdrawal,
            'payment_method' => PaymentMethod::Cash,
            'movement_type' => MovementType::Sangria,
            'value' => $validated['value'],
            'description' => $validated['description'],
            'source_type' => 'cash_register',
            'source_id' => $caixa->id,
        ]);

        return redirect()->route('caixa.index')
            ->with('success', 'Sangria registrada com sucesso.');
    }

     /**
     * Registra um suprimento - inserção de valor para troco.
     */
    public function suprimento(Request $request)
    {
        $caixa = CashRegister::where('status', CashRegisterStatus::Open)->first();

        if (! $caixa) {
            return redirect()->route('caixa.index')
                ->with('error', 'Nenhum caixa aberto.');
        }

        $validated = $request->validate([
            'value' => 'required|integer|min:1',
            'description' => 'required|string|max:500',
        ]);

        CashFlow::create([
            'cash_register_id' => $caixa->id,
            'user_id' => Auth::id(),
            'type' => CashFlowType::Entry,
            'payment_method' => PaymentMethod::Cash,
            'movement_type' => MovementType::Suprimento,
            'value' => $validated['value'],
            'description' => $validated['description'],
            'source_type' => 'cash_register',
            'source_id' => $caixa->id,
        ]);

        return redirect()->route('caixa.index')
            ->with('success', 'Suprimento registrado com sucesso.');
    }

    /**
     * Exibe o formulário de fechamento do caixa.
     */
    public function criarFechamento()
    {
        $caixa = CashRegister::where('status', CashRegisterStatus::Open)->first();

        if (! $caixa) {
            return redirect()->route('caixa.index')
                ->with('error', 'Nenhum caixa aberto.');
        }

        // Calcula o saldo esperado (só dinheiro físico)
        $movimentacoes = CashFlow::where('cash_register_id', $caixa->id)->get();

        $totalEntradas = $movimentacoes->where('type', CashFlowType::Entry->value)->sum('value');
        $totalSaidas = $movimentacoes->where('type', CashFlowType::Withdrawal->value)->sum('value');
        $expectedValue = $totalEntradas - $totalSaidas;

        return view('caixa.fechar', compact('caixa', 'expectedValue'));
    }

    /**
     * Fecha o caixa.
     */
    public function fechar(Request $request)
    {
        $caixa = CashRegister::where('status', CashRegisterStatus::Open)->first();

        if (! $caixa) {
            return redirect()->route('caixa.index')
                ->with('error', 'Nenhum caixa aberto.');
        }

        $validated = $request->validate([
            'actual_value' => 'required|integer|min:0',
            'observations' => 'nullable|string|max:1000',
        ]);

        // Recalcula o saldo esperado
        $totalEntradas = CashFlow::where('cash_register_id', $caixa->id)
            ->where('type', CashFlowType::Entry->value)
            ->sum('value');
        $totalSaidas = CashFlow::where('cash_register_id', $caixa->id)
            ->where('type', CashFlowType::Withdrawal->value)
            ->sum('value');
        $expectedValue = $totalEntradas - $totalSaidas;

        $difference = $expectedValue - $validated['actual_value'];

        // Se houver diferença a justificativa é obrigatória
        if ($difference !== 0 && empty($validated['observations'])) {
            return redirect()->back()
                ->with('error', 'Há diferença no caixa. Informe uma justificativa.')
                ->withInput();
        }

        $caixa->update([
            'closing_date' => now(),
            'expected_value' => $expectedValue,
            'actual_value' => $validated['actual_value'],
            'difference' => $difference,
            'status' => CashRegisterStatus::Closed,
            'observations' => $validated['observations'] ?? null,
        ]);

        if ($difference === 0) {
            $mensagem = 'Caixa fechado com sucesso. Sem divergências.';
        } elseif ($difference > 0) {
            $mensagem = "Caixa fechado com sobra de R$ " . number_format($difference / 100, 2, ',', '.');
        } else {
            $mensagem = "Caixa fechado com falta de R$ " . number_format(abs($difference) / 100, 2, ',', '.');
        }

        return redirect()->route('caixa.index')
            ->with('success', $mensagem);
    }

    public function criarSangria()
    {
        $caixa = CashRegister::where('status', CashRegisterStatus::Open)->first();

        if (! $caixa) {
            return redirect()->route('caixa.index')
                ->with('error', 'Nenhum caixa aberto.');
        }

        return view('caixa.sangria');
    }

    public function criarSuprimento()
    {
        $caixa = CashRegister::where('status', CashRegisterStatus::Open)->first();

        if (! $caixa) {
            return redirect()->route('caixa.index')
                ->with('error', 'Nenhum caixa aberto.');
        }

        return view('caixa.suprimento');
    }

    public function fluxo(Request $request)
    {
        $query = CashFlow::with('user');

        // Filtro por tipo de movimentação
        if ($request->filled('movement_type')) {
            $query->where('movement_type', $request->movement_type);
        }

        // Filtro por data inicial
        if ($request->filled('date_start')) {
            $query->whereDate('created_at', '>=', $request->date_start);
        }

        // Filtro por data final
        if ($request->filled('date_end')) {
            $query->whereDate('created_at', '<=', $request->date_end);
        }

        $movimentacoes = $query->orderBy('created_at', 'desc')->paginate(50)->withQueryString();
        return view('caixa.fluxo', compact('movimentacoes'));
    }
}
