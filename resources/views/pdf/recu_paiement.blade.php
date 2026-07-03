<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family: DejaVu Sans, sans-serif;
            font-size:13px;
            color:#222;
            position:relative;
        }

        .watermark{
            position:fixed;
            top:38%;
            left:10%;
            font-size:70px;
            color:#f2f2f2;
            transform:rotate(-30deg);
            z-index:-1;
            font-weight:bold;
        }

        .header{
            background:#1a2332;
            color:white;
            padding:20px;
            text-align:center;
        }

        .header h1{
            font-size:18px;
            margin-bottom:4px;
        }

        .header p{
            font-size:11px;
            opacity:0.75;
        }

        .recu-body{
            padding:24px;
        }

        .recu-number{
            border:2px solid #1a2332;
            border-radius:6px;
            padding:8px 16px;
            display:inline-block;
            font-weight:bold;
            font-size:14px;
            margin-bottom:5px;
        }

        .payment-id{
            margin-bottom:20px;
            font-size:11px;
            color:#666;
        }

        .info-grid{
            width:100%;
            border-collapse:collapse;
            margin-bottom:20px;
        }

        .info-grid td{
            padding:6px 8px;
            vertical-align:top;
        }

        .label{
            color:#666;
            font-size:11px;
            text-transform:uppercase;
        }

        .value{
            font-weight:bold;
        }

        hr{
            border:none;
            border-top:1px solid #ddd;
            margin:16px 0;
        }

        .montant-bloc{
            background:#f0f9f4;
            border:2px solid #28a745;
            border-radius:8px;
            padding:16px;
            text-align:center;
            margin:20px 0;
        }

        .montant-label{
            font-size:11px;
            color:#666;
            text-transform:uppercase;
        }

        .montant-val{
            font-size:26px;
            font-weight:bold;
            color:#28a745;
        }

        .reste-bloc{
            background: {{ $reste_a_payer > 0 ? '#fff8f0' : '#f0f9f4' }};
            border-left:4px solid {{ $reste_a_payer > 0 ? '#fd7e14' : '#28a745' }};
            padding:12px 16px;
            margin-bottom:20px;
        }

        .footer{
            text-align:center;
            font-size:10px;
            color:#888;
            margin-top:30px;
            border-top:1px solid #eee;
            padding-top:12px;
        }
    </style>
</head>

<body>

    <div class="watermark">
        REÇU OFFICIEL
    </div>

    <div class="header">
        <h1>École Primaire Boussougou Communale — Reçu de paiement</h1>
        <p>Système de Gestion de Scolarité</p>
    </div>

    <div class="recu-body">

        <div class="recu-number">
            Reçu N° {{ str_pad($paiement->id, 6, '0', STR_PAD_LEFT) }}
        </div>

        <div class="payment-id">
            Paiement #{{ $paiement->id }}
        </div>

        <table class="info-grid">

            <tr>
                <td>
                    <div class="label">Élève</div>
                    <div class="value">{{ $eleve->nom_complet }}</div>
                </td>

                <td>
                    <div class="label">Classe</div>
                    <div class="value">{{ $classe->nom }}</div>
                </td>
            </tr>

            <tr>
                <td>
                    <div class="label">Date du versement</div>
                    <div class="value">
                        {{ $paiement->date_paiement->format('d/m/Y') }}
                    </div>
                </td>

                <td>
                    <div class="label">Année scolaire</div>
                    <div class="value">
                        {{ $classe->annee_scolaire }}
                    </div>
                </td>
            </tr>

            <tr>
                <td>
                    <div class="label">Parent responsable</div>
                    <div class="value">
                        {{ $eleve->nom_parent ?? 'Non renseigné' }}
                    </div>
                </td>

                <td>
                    <div class="label">Téléphone</div>
                    <div class="value">
                        {{ $eleve->telephone_parent ?? '-' }}
                    </div>
                </td>
            </tr>

        </table>

        <hr>

        <div class="montant-bloc">
            <div class="montant-label">
                Montant reçu
            </div>

            <div class="montant-val">
                {{ number_format($paiement->montant, 0, ',', ' ') }}
                F CFA
            </div>
        </div>

        <table class="info-grid">

            <tr>
                <td class="label">
                    Frais annuels de la classe
                </td>

                <td class="value">
                    {{ number_format($classe->frais_scolarite, 0, ',', ' ') }}
                    F CFA
                </td>
            </tr>

            <tr>
                <td class="label">
                    Total versé à ce jour
                </td>

                <td class="value" style="color:#28a745">
                    {{ number_format($total_paye, 0, ',', ' ') }}
                    F CFA
                </td>
            </tr>

        </table>

        <div class="reste-bloc">

            @if($reste_a_payer > 0)

                <strong>
                    Reste à payer :
                    {{ number_format($reste_a_payer, 0, ',', ' ') }}
                    F CFA
                </strong>

            @else

                <strong style="color:#28a745">
                    ✓ Compte soldé — Aucun montant restant dû
                </strong>

            @endif

        </div>

        @if($paiement->observations)

            <p>
                <strong>Observations :</strong>
                {{ $paiement->observations }}
            </p>

        @endif

        <div class="footer">

            <p>
                Reçu généré le
                {{ now()->format('d/m/Y à H:i') }}
            </p>

            <p>
                Ce document fait foi de paiement.
            </p>

            <p>
                École Primaire Boussougou Communale —
                Gestion Scolaire Numérique
            </p>

        </div>

    </div>

</body>
</html>
