<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Data Pemasukan</title>
    <style>
        body {
            font-family: 'Manrope', Arial, Helvetica, sans-serif;
        }

        h2 {
            text-align: center;
        }

        .title {
            font-size: 14px;
            font-weight: normal;
        }

        .sub-head {
            font-size: 14px;
            font-weight: normal;
            text-align: center;
            margin-top: -10px;
        }

        .heading {
            width: 100%;
        }

        table {
            width: 100%;
            border: 1px solid black;
            border-collapse: collapse;
        }

        tr, th, td {
            border: 1px solid black;
            padding: 10px;
            text-align: left;
        }

        th[colspan="2"] {
            text-align: center;
        }

        .sumber {
            text-transform: capitalize;
        }
    </style>
</head>

<body>
    <p class="title">Nama Store/User = <b>{{ $user->name }}</b></p>
    <div class="heading">
        <h2>Laporan Penghasilan Tahun {{ $tahun }}</h2>
        <p class="sub-head">Pelaporan penghasilan pertahun ini mencakup data tahun yang sudah dipilih pada filter dan list data penghasilan sekaligus total perbulan pada tahun yang dipilih.</p>
    </div>
    <div class="table">
        <p>Keseluruhan Total : <b>Rp. {{ number_format($totalPertahun) }}</b></p>
        <table>
            <thead>
                <tr>
                    <th colspan="2">Bulan</th>
                    <th colspan="2">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($monthlyIncome as $bulan)
                    <tr>
                        <td colspan="2">{{ $bulan['bulan'] }}</td>
                        <td colspan="2">Rp. {{ number_format($bulan['total_nominal']) }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <th>Sumber</th>
                        <th colspan="2">Nominal</th>
                    </tr>
                    @foreach ($bulan['data_pemasukan'] as $pemasukan)
                        <tr>
                            <td>{{ $pemasukan->created_at->format('d F Y') }}</td>
                            <td class="sumber">{{ $pemasukan->sumber }}</td>
                            <td colspan="2">Rp. {{ number_format($pemasukan->nominal) }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
