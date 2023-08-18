$('#submitForm').submit(function(e) {
    e.preventDefault();
    $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
    });
    $.ajax({
        url: $(this).attr('action'),
        method: $(this).attr('method'),
        data: $(this).serialize(),
        success: function(response) {
            console.log(response.status);
            if (response.status == false) {
                showAlert(response.error);
            }else{
                // Panggil fungsi pencetakan setelah formulir berhasil dikirim
                printDocument(response);
                // redirectToPage('/berhasil');

            }
        },
        error: function(xhr, status, error) {
            // Tangani kesalahan jika terjadi
            console.log(error);
        }
    });
})
function showAlert(status) {
    var alertHtml = ` <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                        <div>
                            <strong>Terjadi kesalahan!</strong> ${status}
                        </div>
                    </div>`
    var alertElement = $(alertHtml).appendTo("#pesan_error");
    setTimeout(function() {
        alertElement.alert('close');
    }, 3000);
}
function printDocument(response) {
    // Kirim HTML ke server untuk menghasilkan file PDF
    $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
    });
    $.ajax({
    url:`{{ route('setor-tunai.pdf') }}`,
    method: 'POST',
    data: {transaction: response.transaction},
    success: function(response) {
        var receiptUrl = response.file_path;
        // console.log(receiptUrl);
        var link = document.createElement('a');
        link.href = receiptUrl;
        link.download = 'receipt.pdf';
        link.target = '_blank'; // Untuk membuka tautan unduhan dalam tab baru
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        setTimeout(redirectToPage(`{{ route('teller.informasi.nasabah') }}`), 50000);
    },
    error: function(xhr, status, error) {
        // Tangani kesalahan jika terjadi
        console.log(error);
    }
    });
}
function redirectToPage(url) {
    window.location.href = url;
}
