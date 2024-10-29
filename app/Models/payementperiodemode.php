<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class payementperiodemode extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'user_id',
        'order_id',
        'total_amount',
        'period',
        'month_1',
        'month_2',
        'month_3',
        'month_4',
        'month_5',
        'month_6',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function distributeAmountOverMonths($totalAmount, $period)
    {
        $monthlyAmount = $totalAmount / $period; // Répartition par la période choisie

        $this->month_1 = $monthlyAmount;
        if ($period >= 2) $this->month_2 = $monthlyAmount;
        if ($period >= 3) $this->month_3 = $monthlyAmount;
        if ($period >= 4) $this->month_4 = $monthlyAmount;
        if ($period >= 5) $this->month_5 = $monthlyAmount;
        if ($period == 6) $this->month_6 = $monthlyAmount;
    }



    public function payement($total_amount, $period, $month_1 = null, $month_2 = null, $month_3 = null, $month_4 = null, $month_5 = null, $month_6 = null)
    {
        // Validation des données
        if (is_null($total_amount) || !is_numeric($total_amount) || $total_amount <= 0) {
            return response()->json(['message' => 'Le montant total est requis et doit être un nombre positif.'], 400);
        }

        if (is_null($period) || !is_integer($period) || $period < 0 || $period > 6) {
            return response()->json(['message' => 'La période est requise et doit être un entier entre 0 et 6.'], 400);
        }

        // Si la période est 0, définissons la période à 6 par défaut
        $period = $period === 0 ? 6 : $period;

        // Créer un paiement
        $payment = new payementperiodemode();
        $payment->user_id = 1; // Utilisateur connecté
        $payment->total_amount = $total_amount;
        $payment->period = $period;

        // Récupération des montants des mois
        $months = [
            $month_1 ?? 0,
            $month_2 ?? 0,
            $month_3 ?? 0,
            $month_4 ?? 0,
            $month_5 ?? 0,
            $month_6 ?? 0,
        ];

        // Vérifier la somme des montants
        $totalMonthsAmount = array_sum($months);

        // Si la somme des montants est nulle, répartir le montant
        if ($totalMonthsAmount === 0) {
            $payment->distributeAmountOverMonths($total_amount, $period);
        } else {
            // Affectation des montants
            $payment->month_1 = $month_1;
            if ($period >= 2) $payment->month_2 = $month_2;
            if ($period >= 3) $payment->month_3 = $month_3;
            if ($period >= 4) $payment->month_4 = $month_4;
            if ($period >= 5) $payment->month_5 = $month_5;
            if ($period == 6) $payment->month_6 = $month_6;
        }

        // Vérifier si la somme des montants est inférieure au total_amount
        if ($totalMonthsAmount < $total_amount) {
            return response()->json([
                'message' => 'La somme est inférieure au montant d\'achat. Veuillez bien renseigner les champs.',
                'data' => $payment
            ], 400); // 400 Bad Request
        }

        // Sauvegarder le paiement
        $payment->save();

        // Retourner une réponse JSON avec le paiement créé
        return response()->json([
            'message' => 'Paiement ajouté avec succès.',
            'data' => $payment
        ], 201); // 201 Created
    }

}
