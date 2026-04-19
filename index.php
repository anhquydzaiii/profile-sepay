<?php
include 'db.php';

$admin = [
    'name'       => 'BÙI ANH QUÝ',
    'avatar'     => 'https://files.catbox.moe/l8sibv.png', 
    'bio'        => 'Digital Architect • Creative Developer',
    'bank_bin'   => '970422', 
    'bank_acc'   => '1313052008', 
];

// Logic Server (Giữ nguyên)
if (isset($_POST['action']) && $_POST['action'] == 'create_qr') die(json_encode(['code' => "Donate" . rand(1000, 9999)]));
if (isset($_GET['check_txn'])) {
    $stmt = $conn->prepare("SELECT id FROM nap_tien_log WHERE content = ? LIMIT 1");
    $stmt->bind_param("s", $_GET['check_txn']);
    $stmt->execute();
    die(json_encode($stmt->get_result()->num_rows > 0 ? ['status' => 'success'] : ['status' => 'pending']));
}
if (isset($_GET['get_all_logs'])) {
    $logs = $conn->query("SELECT * FROM nap_tien_log ORDER BY id DESC");
    $data = [];
    while($r = $logs->fetch_assoc()) {
        $data[] = [
            'username' => htmlspecialchars($r['username']),
            'content'  => $r['content'],
            'amount'   => number_format($r['amount']),
            'initial'  => mb_substr($r['username'], 0, 1)
        ];
    }
    die(json_encode($data));
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $admin['name']; ?> | Modern Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        
        body {
            background: #020408;
            color: #e2e8f0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        /* Hiệu ứng nền Moving Mesh */
        .bg-glow {
            position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);
            width: 600px; height: 600px; background: radial-gradient(circle, rgba(59,130,246,0.15) 0%, transparent 70%);
            filter: blur(80px); z-index: -1;
        }

        /* Container chính gộp tất cả */
        .super-bento {
            background: rgba(13, 17, 23, 0.8);
            backdrop-filter: blur(30px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 3rem;
            box-shadow: 0 40px 100px -20px rgba(0,0,0,0.8);
            overflow: hidden;
        }

        /* Divider mảnh phân chia nội dung */
        .section-divider { border-color: rgba(255, 255, 255, 0.05); }

        /* Custom Input */
        .custom-input {
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .custom-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.1);
            background: rgba(0, 0, 0, 0.4);
        }

        /* Animation cho các dòng log */
        .log-item { animation: slideIn 0.5s ease backwards; }
        @keyframes slideIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        .btn-primary {
            background: #fff; color: #000;
            transition: all 0.3s;
        }
        .btn-primary:hover { transform: scale(1.02); box-shadow: 0 10px 30px rgba(255,255,255,0.1); }
        .btn-primary:active { transform: scale(0.98); }
    </style>
</head>
<body>
    <div class="bg-glow"></div>

    <div class="w-full max-w-5xl super-bento grid grid-cols-1 md:grid-cols-12 h-auto md:min-h-[700px]">
        
        <div class="md:col-span-4 p-8 md:p-12 section-divider border-b md:border-b-0 md:border-r flex flex-col justify-between">
            <div>
                <div class="relative group w-32 h-32 mx-auto mb-8">
                    <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-[2.5rem] blur opacity-25 group-hover:opacity-50 transition duration-1000"></div>
                    <img src="<?php echo $admin['avatar']; ?>" class="relative w-32 h-32 rounded-[2.2rem] object-cover border border-white/10 shadow-2xl">
                </div>
                
                <div class="text-center md:text-left">
                    <h1 class="text-2xl font-extrabold tracking-tighter mb-2"><?php echo $admin['name']; ?></h1>
                    <p class="text-[10px] font-black text-blue-500 uppercase tracking-[0.2em] mb-8"><?php echo $admin['bio']; ?></p>
                    
                    <div class="space-y-6">
                        <p class="text-[9px] font-bold text-gray-500 uppercase tracking-widest">Core Stack</p>
                        <div class="space-y-4">
                            <?php 
                            $skills = [['Backend', 95, 'blue'], ['Security', 88, 'purple'], ['UI/UX', 92, 'emerald']];
                            foreach($skills as $sk): ?>
                            <div class="group">
                                <div class="flex justify-between text-[9px] font-bold uppercase mb-1.5 opacity-60 group-hover:opacity-100 transition-opacity">
                                    <span><?php echo $sk[0]; ?></span>
                                    <span><?php echo $sk[1]; ?>%</span>
                                </div>
                                <div class="h-[3px] bg-white/5 rounded-full overflow-hidden">
                                    <div class="h-full bg-<?php echo $sk[2]; ?>-500 shadow-[0_0_8px_rgba(59,130,246,0.5)]" style="width: <?php echo $sk[1]; ?>%"></div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex gap-4 mt-12 justify-center md:justify-start">
                <a href="https://facebook.com/anhquyhacker" class="text-gray-500 hover:text-white transition-colors text-lg"><i class="fa-brands fa-facebook"></i></a>
                <a href="https://tiktok.com/@anhquydev" class="text-gray-500 hover:text-white transition-colors text-lg"><i class="fa-brands fa-tiktok"></i></a>
                <a href="#" class="text-gray-500 hover:text-white transition-colors text-lg"><i class="fa-brands fa-telegram"></i></a>
            </div>
        </div>

        <div class="md:col-span-8 flex flex-col">
            <div class="p-8 md:p-12 section-divider border-b">
                <div class="flex items-center gap-4 mb-10">
                    <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-500 border border-blue-500/20">
                        <i class="fa-solid fa-bolt-lightning text-sm"></i>
                    </div>
                    <h2 class="text-sm font-black uppercase tracking-widest">Hỗ trợ dự án</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <input type="text" id="nameInp" placeholder="TÊN BẠN..." class="custom-input w-full p-4 rounded-2xl text-[10px] font-black outline-none tracking-widest uppercase">
                    <input type="number" id="amtInp" placeholder="SỐ TIỀN..." class="custom-input w-full p-4 rounded-2xl text-[10px] font-black outline-none text-blue-400">
                </div>

                <button onclick="createQR()" id="btnCreate" class="btn-primary w-full py-4 rounded-2xl text-[10px] font-black uppercase tracking-[0.3em]">
                    Khởi tạo thanh toán
                </button>

                <div id="qrArea" class="hidden mt-8 p-6 rounded-[2rem] bg-white/[0.02] border border-white/5 animate-in slide-in-from-bottom-2 duration-500">
                    <div class="flex flex-col sm:flex-row items-center gap-8">
                        <div class="bg-white p-2 rounded-2xl shadow-2xl">
                            <img id="qrImg" class="w-32 h-32 rounded-lg">
                        </div>
                        <div class="flex-1 space-y-4 text-center sm:text-left">
                            <p class="text-[9px] text-gray-500 font-bold uppercase tracking-widest">Nội dung chuyển khoản</p>
                            <div onclick="copyText()" class="inline-block px-5 py-3 bg-black/40 border border-white/5 rounded-xl cursor-pointer hover:border-blue-500/50 transition-all group">
                                <span id="qrCode" class="text-xl font-mono font-black text-blue-400 group-hover:text-blue-300"></span>
                            </div>
                            <p class="text-[8px] text-gray-600 font-medium italic"><i class="fa-solid fa-spinner fa-spin mr-2"></i>Chờ xác nhận tự động (10-30s)</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-8 md:p-12 flex-1 flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-center mb-8">
                        <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-500">Người đã ủng hộ</h3>
                        <div class="flex items-center gap-4">
                            <span id="pageInfo" class="text-[9px] font-bold text-gray-600">1 / 1</span>
                            <div class="flex gap-1">
                                <button onclick="prevPage()" id="prevBtn" class="w-8 h-8 rounded-lg border border-white/5 flex items-center justify-center hover:bg-white/5 transition-all disabled:opacity-20"><i class="fa-solid fa-chevron-left text-[10px]"></i></button>
                                <button onclick="nextPage()" id="nextBtn" class="w-8 h-8 rounded-lg border border-white/5 flex items-center justify-center hover:bg-white/5 transition-all disabled:opacity-20"><i class="fa-solid fa-chevron-right text-[10px]"></i></button>
                            </div>
                        </div>
                    </div>

                    <div id="logContainer" class="space-y-3 min-h-[220px]">
                        </div>
                </div>

                <div class="mt-8 pt-8 border-t border-white/5 text-center md:text-left">
                    <p class="text-[8px] font-bold text-gray-700 uppercase tracking-[0.5em]">Frame V19 Pro • <?php echo date('Y'); ?> Design</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        let allLogs = [];
        let currentPage = 1;
        const rowsPerPage = 4;

        $(document).ready(() => fetchLogs());

        function fetchLogs() {
            $.getJSON('?get_all_logs=true', (data) => {
                allLogs = data;
                renderLogs();
            });
        }

        function renderLogs() {
            const start = (currentPage - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            const paginatedItems = allLogs.slice(start, end);
            const totalPages = Math.ceil(allLogs.length / rowsPerPage) || 1;

            $('#pageInfo').text(`${currentPage} / ${totalPages}`);
            $('#prevBtn').prop('disabled', currentPage === 1);
            $('#nextBtn').prop('disabled', currentPage === totalPages);

            let html = '';
            if (paginatedItems.length === 0) {
                html = '<div class="text-center py-12 text-gray-700 text-[10px] font-bold tracking-[0.2em]">CHƯA CÓ DỮ LIỆU</div>';
            } else {
                paginatedItems.forEach((log, i) => {
                    html += `
                    <div class="log-item flex justify-between items-center p-4 rounded-2xl bg-white/[0.02] border border-white/5 hover:border-white/10 transition-all group" style="animation-delay: ${i*0.05}s">
                        <div class="flex items-center gap-4">
                            <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-blue-500/10 to-indigo-500/10 flex items-center justify-center text-[10px] font-black text-blue-400 border border-white/5">${log.initial}</div>
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-tight">${log.username}</p>
                                <p class="text-[8px] text-gray-600 font-bold">${log.content}</p>
                            </div>
                        </div>
                        <p class="text-[10px] font-black text-emerald-500">+${log.amount}đ</p>
                    </div>`;
                });
            }
            $('#logContainer').html(html);
        }

        function nextPage() { if(currentPage * rowsPerPage < allLogs.length) { currentPage++; renderLogs(); } }
        function prevPage() { if(currentPage > 1) { currentPage--; renderLogs(); } }

        function copyText() {
            navigator.clipboard.writeText($('#qrCode').text());
            Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Đã copy nội dung!', showConfirmButton: false, timer: 1500, background: '#111', color: '#fff' });
        }

        function createQR() {
            let n = $('#nameInp').val(), a = $('#amtInp').val();
            if(!n || a < 1000) return Swal.fire({ icon:'warning', title:'Lỗi', text:'Vui lòng điền đủ thông tin!', background:'#0d1117', color:'#fff' });

            $('#btnCreate').html('<i class="fa-solid fa-circle-notch fa-spin mr-2"></i> Đang khởi tạo...').prop('disabled', true).addClass('opacity-50');
            
            $.post('', {action:'create_qr'}, function(res){
                let d = JSON.parse(res);
                let content = d.code + " " + n.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toUpperCase();
                
                $('#qrImg').attr('src', `https://img.vietqr.io/image/<?php echo $admin['bank_bin']; ?>-<?php echo $admin['bank_acc']; ?>-compact2.png?amount=${a}&addInfo=${encodeURIComponent(content)}`);
                $('#qrCode').text(content);
                $('#qrArea').hide().removeClass('hidden').slideDown(600);
                $('#btnCreate').text('Cập nhật mã mới').prop('disabled', false).removeClass('opacity-50');

                let check = setInterval(() => {
                    $.getJSON(`?check_txn=${d.code}`, (r) => {
                        if(r.status === 'success') {
                            clearInterval(check);
                            $('#qrArea').slideUp();
                            fetchLogs();
                            Swal.fire({ icon:'success', title:'Xác nhận', text:'Cảm ơn bạn đã ủng hộ!', background:'#0d1117', color:'#fff' });
                        }
                    });
                }, 3000);
            });
        }
    </script>
</body>
</html>