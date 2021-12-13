@extends('layouts.master')

@section('title')
    Transaksi Penjualan
@endsection

@push('css')
<style>
    .tampil-bayar {
        font-size: 5em;
        text-align: center;
        height: 100px;
    }

    .tampil-terbilang {
        padding: 10px;
        background: #f0f0f0;
    }

    .table-penjualan tbody tr:last-child {
        display: none;
    }

    @media(max-width: 768px) {
        .tampil-bayar {
            font-size: 3em;
            height: 70px;
            padding-top: 5px;
        }
    }
</style>
@endpush

@section('breadcrumb')
    @parent
    <li class="active">Cashier</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-body">
                    
                <form class="form-produk">
                    @csrf
                    <div class="form-group row">
                        <label for="kode_produk" class="col-lg-2">Product Code</label>
                        <div class="col-lg-5">
                            <div class="input-group">
                                <input type="hidden" name="id_penjualan" id="id_penjualan" value="{{ $id_penjualan }}">
                                <input type="hidden" name="id_produk" id="id_produk">
                                <input type="text" class="form-control" name="kode_produk" id="kode_produk" readonly>
                                <span class="input-group-btn">
                                    <button onclick="tampilProduk()" class="btn btn-info btn-flat" type="button"><i class="fa fa-arrow-right"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>

                <table class="table table-stiped table-bordered table-penjualan">
                    <thead>
                        <th width="5%">No</th>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th width="15%">Quantity</th>
                        <!-- <th>Diskon</th> -->
                        <th>Subtotal</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="tampil-bayar bg-primary"></div>
                        <div class="tampil-terbilang"></div>
                    </div>
                    <div class="col-lg-4">
                        <form action="{{ route('transaksi.simpan') }}" class="form-penjualan" method="post">
                            @csrf
                            <input type="hidden" name="id_penjualan" value="{{ $id_penjualan }}">
                            <input type="hidden" name="total" id="total">
                            <input type="hidden" name="total_item" id="total_item">
                            <input type="hidden" name="bayar" id="bayar">
                            <input type="hidden" name="id_member" id="id_member" value="{{ $memberSelected->id_member }}">

                            <div class="form-group row">
                                <label for="totalrp" class="col-lg-2 control-label">Total</label>
                                <div class="col-lg-8">
                                    <input type="text" id="totalrp" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="kode_member" class="col-lg-2 control-label">Member</label>
                                <div class="col-lg-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="kode_member" value="{{ $memberSelected->kode_member }}" readonly>
                                        <span class="input-group-btn">
                                            <button onclick="tampilMember()" class="btn btn-info btn-flat" type="button"><i class="fa fa-arrow-right"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="point" class="col-lg-2 control-label">Available Point</label>
                                <div class="col-lg-8">
                                    <input type="text" id="available_point" name="available_point" class="form-control" value="#" readonly>
                                </div>
                            </div>
                            <!-- <div class="form-group row">
                                <label for="diskon" class="col-lg-2 control-label">Diskon</label>
                                <div class="col-lg-8">
                                    <input type="number" name="diskon" id="diskon" class="form-control" 
                                        value="{{ ! empty($memberSelected->id_member) ? $diskon : 0 }}" 
                                        readonly>
                                </div>
                            </div> -->
                            <div class="form-group row">
                                <label for="bayar" class="col-lg-2 control-label">Payment</label>
                                <div class="col-lg-8">
                                    <input type="text" id="bayarrp" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="diterima" class="col-lg-2 control-label">Amount Received</label>
                                <div class="col-lg-8">
                                    <input type="number" id="diterima" class="form-control" name="diterima" value="{{ $penjualan->diterima ?? 0 }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="kembali" class="col-lg-2 control-label">Kembali</label>
                                <div class="col-lg-8">
                                    <input type="text" id="kembali" name="kembali" class="form-control" value="0" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="point" class="col-lg-2 control-label">Rewarded Point</label>
                                <div class="col-lg-8">
                                    <input type="text" id="rewarded_point" name="rewarded_point" class="form-control" value="#" readonly>
                                </div>
                            </div>

                            <div class="form-group row">
                            <div class="col-lg-6">
                            <select name="method" id="method" class="form-control" required>
                                <option value="" required>Payment Method</option>
                                @foreach ($payment_method as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
                                @endforeach
                            </select>
                            <span class="help-block with-errors"></span>
                        </div>
                            </div>

                            <div class="form-group row">
                                <label for="use_point" class="col-lg-2 control-label">Use Point?</label>
                                <div class="col-lg-8">
                                    <input type="checkbox" id="use_point" name="use_point" value="1" disabled>
                                </div>
                            </div>


                        </form>
                    </div>
                </div>
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary btn-sm btn-flat pull-right btn-simpan" id="submitTransactionButton" disabled><i class="fa fa-floppy-o"></i> Proceed Payment</button>
            </div>
        </div>
    </div>
</div>

@includeIf('penjualan_detail.produk')
@includeIf('penjualan_detail.member')
@endsection

@push('scripts')
<script>
    let table, table2;

    $(function () {
        setInterval(function(){
            getPointPerMember();
        }, 1000);

        $('body').addClass('sidebar-collapse');

        table = $('.table-penjualan').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('transaksi.data', $id_penjualan) }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'kode_produk'},
                {data: 'nama_produk'},
                {data: 'harga_jual'},
                {data: 'jumlah'},
                // {data: 'diskon'},
                {data: 'subtotal'},
                // {data: 'method'},
                {data: 'aksi', searchable: false, sortable: false},
            ],
            dom: 'Brt',
            bSort: false,
            paginate: false
        })
        .on('draw.dt', function () {
            loadForm($('#diskon').val());
            setTimeout(() => {
                $('#diterima').trigger('input');
            }, 300);
        });
        table2 = $('.table-produk').DataTable();

        $(document).on('input', '.quantity','.stok', function () {
            let id = $(this).data('id');
            let jumlah = parseInt($(this).val());

            if (jumlah < 1) {
                $(this).val(1);
                alert('Jumlah tidak boleh kurang dari 1');
                return;
            }
            
            if (jumlah > 10000) {
                $(this).val(10000);
                alert('Jumlah tidak boleh lebih dari 10000');
                return;
            }

            // if (jumlah > stok) {
            //     $(this).val();
            //     alert('Stok tidak mencukupi');
            //     return;
            // }

            $.post(`{{ url('/transaksi') }}/${id}`, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'put',
                    'jumlah': jumlah
                })
                .done(response => {
                    $(this).on('mouseout', function () {
                        table.ajax.reload(() => loadForm($('#diskon').val()));
                    });
                })
                .fail(errors => {
                    alert('Tidak dapat menyimpan data');
                    return;
                });
        });

        $(document).on('input', '#diskon', function () {
            if ($(this).val() == "") {
                $(this).val(0).select();
            }

            loadForm($(this).val());
        });

        $('#diterima').on('input', function () {
            console.log($(this).val());
            if ($(this).val() == "") {
                $(this).val(0).select();
                $('#submitTransactionButton').attr('disabled',true);
            }else{
                if($(this).val() >= parseInt($('#bayarrp').val().replace('Rp. ','').replace(/\./g, ""))){
                    console.log('abababa');
                    $('#submitTransactionButton').attr('disabled',false);
                }else{
                    console.log('cnccbcb');
                    $('#submitTransactionButton').attr('disabled',true);
                }                
                
            }

            loadForm($('#diskon').val(), $(this).val());
        }).focus(function () {
            $(this).select();
        });

        $('.btn-simpan').on('click', function () {
            $('.form-penjualan').submit();
        });

        $('[name=use_point]').change(function(){
            if(this.checked){
                var original_price = parseInt($('#bayarrp').val().replace('Rp. ','').replace(/\./g, ""));
                var point_amount = parseInt($('[name=available_point]').val());
                $('.total').text(original_price - point_amount);
                $('#bayarrp').val(original_price - point_amount);
                $('#rewarded_point').val("#");
                // console.log($('#bayarrp').val().replace('Rp. ','').replace(/\./g, ""));
            }else{
                // console.log('noooo');
                var original_price = parseInt($('#bayarrp').val().replace('Rp. ','').replace(/\./g, ""));
                var point_amount = parseInt($('[name=available_point]').val());                
                $('.total').text(original_price + point_amount);
                $('#bayarrp').val(original_price + point_amount);
                $('#rewarded_point').val(Math.floor((original_price + point_amount) / 1000));
            }
        })
    });

    function tampilProduk() {
        $('#modal-produk').modal('show');
    }

    function hideProduk() {
        $('#modal-produk').modal('hide');
    }

    function pilihProduk(id, kode, stok) {
        // if (jumlah > 0){
        $('#id_produk').val(id);
        $('#kode_produk').val(kode);
        hideProduk();
        tambahProduk();
        // }    
    }
    
    

    function tambahProduk() {
        $.post('{{ route('transaksi.store') }}', $('.form-produk').serialize())
            .done(response => {
                $('#kode_produk').focus();
                table.ajax.reload(() => loadForm($('#diskon').val()));
            })
            .fail(errors => {
                alert('Tidak dapat menyimpan data');
                return;
            });
        
    }

    function tampilMember() {
        $('#modal-member').modal('show');
    }

    function pilihMember(id, kode) {
        $('#id_member').val(id);
        $('#kode_member').val(kode);
        $('#diskon').val('{{ $diskon }}');
        loadForm($('#diskon').val());
        $('#diterima').val(0).focus().select();
        getPointByMember(id);
        hideMember();
    }

    function hideMember() {
        $('#modal-member').modal('hide');
    }

    function deleteData(url) {
        if (confirm('Yakin ingin menghapus data terpilih?')) {
            $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'delete'
                })
                .done((response) => {
                    table.ajax.reload(() => loadForm($('#diskon').val()));
                })
                .fail((errors) => {
                    alert('Tidak dapat menghapus data');
                    return;
                });
        }
    }

    function loadForm(diskon = 0, diterima = 0) {
        // console.log('bababa');
        // var total = $('.total').text();
        // if($('[name=available_point]').val() != "#" && $('[name=use_point]').attr('checked') == true){
        //     total = $('.total').text() - $('[name=available_point]').val();
        //     console.log(total);
        //     $('.total').text(total);
        // }
        $('#total').val($('.total').text());
        $('#total_item').val($('.total_item').text());

        $.get(`{{ url('/transaksi/loadform') }}/${diskon}/${$('.total').text()}/${diterima}`)
            .done(response => {
                $('#totalrp').val('Rp. '+ response.totalrp);
                $('#bayarrp').val('Rp. '+ response.bayarrp);
                $('#bayar').val(response.bayar);
                $('.tampil-bayar').text('Bayar: Rp. '+ response.bayarrp);
                $('.tampil-terbilang').text(response.terbilang);
                $('#rewarded_point').val(response.bayarrp.replace(/\./g, "")/1000);

                $('#kembali').val('Rp.'+ response.kembalirp);
                if ($('#diterima').val() != 0) {
                    $('.tampil-bayar').text('Kembali: Rp. '+ response.kembalirp);
                    $('.tampil-terbilang').text(response.kembali_terbilang);
                }
            })
            .fail(errors => {
                alert('Tidak dapat menampilkan data');
                return;
            })
    }

    function getPointPerMember(){
        $.ajax({
            "url" : "{{ route('point.get_point_per_member') }}",
            "type" : "GET",
            "success": function(data){
                console.log(data);
            }
        });
    }

    function getPointByMember(member_id){
        $.ajax({
            "url" : "{{ route('point.get_point_by_member') }}"+"/"+member_id,
            "type" : "GET",
            "success": function(data){
                
                console.log(data);
                if(data['total_point'] > 0){
                    $('[name=available_point]').val(data['total_point']);
                    $('[name=use_point]').attr('disabled',false);
                    // loadForm($('#diskon').val(), $(this).val());
                }else{
                    $('[name=available_point]').val("#");
                    $('[name=use_point]').attr('disabled',true);
                    // loadForm($('#diskon').val(), $(this).val());
                }
            }
        });
    }
</script>
@endpush