<?php
if ($attandance) {
    $time_in = $attandance['results']['time_in'] ?? '';
    $time_out = $attandance['results']['time_out'] ?? '';
    
    $date_in = $attandance['results']['date_in'] ?? '';
    $date_out = $attandance['results']['date_out'] ?? '';
    
    $shift_name = $attandance['results']['shift_name'];
    $shift_detail = $attandance['results']['shift_detail'];
    $no_shift = '';
    if (!($shift_name && $shift_detail)) {
        $shift = 'Your shift schedule is unknown';
        $no_shift = 'no-shift';
        $schedule = '';
    } else {
        $start = (new DateTime($attandance['results']['start']))->format('H:i');
        $end   = (new DateTime($attandance['results']['end']))->format('H:i');
        $shift = $shift_name . ' - ' . $shift_detail;
        $schedule = $start . ' - ' . $end;
    }
} else {
    $time_in = '';
    $time_out = '';
    $date_in = '';
    $date_out = '';
    $shift = 'No shift schedule for today';
    $schedule = '';
    $no_shift = 'no-shift';
}
?>
<style>
    /* ================= RESET ================= */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    /* ================= BODY ================= */
    body {
        font-family: Inter, system-ui, sans-serif;
        background: linear-gradient(180deg, #6b6df7, #5f63f2);
        min-height: 100vh;
        color: #fff;
    }

    /* ================= PAGE ================= */
    .page {
        min-height: 80vh;
        padding: 48px 32px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    /* ================= TIME ================= */
    .time {
        text-align: center;
        margin-bottom: 24px;
    }

    .time h1 {
        font-size: 56px !important;
        font-weight: 700;
    }

    .time p {
        font-size: 18px !important;
        opacity: .9;
    }

    /* ================= CARD ================= */
    .card {
        width: 100%;
        max-width: 900px;
        min-height: 768px;
        max-height: 80vh;
        background: #fff;
        color: #333;
        border-radius: 28px;
        padding: 48px 72px;
        box-shadow: 0 40px 80px rgba(0, 0, 0, .2);

        display: grid;
        grid-template-rows: auto auto 1fr auto auto;
        row-gap: 24px;
    }

    /* ================= HEADER ================= */
    .header {
        text-align: center;
    }

    .subtitle {
        font-size: 24px !important;
        color: #999;
    }

    .shift-time {
        font-size: 36px !important;
        font-weight: 600;
        margin-top: 8px;
    }

    .shift-type {
        font-size: 24px !important;
        color: #777;
        margin-top: 6px;
    }

    .no-shift {
        color: #f5473b !important;
    }

    .info {
        text-align: center;
        color: #777;
        font-size: 18px !important;
    }

    /* ================= FINGERPRINT ================= */
    .fingerprint-wrap {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .fingerprint {
        width: 160px;
        height: 160px;
        border-radius: 50%;
        background: linear-gradient(180deg, #6fa3ff, #4b78ff);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        aspect-ratio: 1 / 1;
        transition: .3s;
    }

    .fingerprint:hover {
        transform: scale(1.06);
    }

    .fingerprint img {
        width: 55%;
        height: 55%;
        object-fit: contain;
        filter: brightness(0) invert(1);
    }

    /* ================= LOCATION PILL ================= */
    .location-pill {
        display: flex;
        align-items: center;
        gap: 12px;
        background: #f3f4f8;
        border-radius: 999px;
        padding: 12px 16px;
        max-width: 520px;
        margin: 0 auto;
    }

    .location-icon {
        width: 18px;
        height: 18px;
        object-fit: contain;
    }

    .location-text {
        flex: 1;
        min-width: 0;
        cursor: pointer;
    }

    #locationAddress {
        font-size: 14px !important;
        color: #666;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .location-text:hover #locationAddress {
        text-decoration: underline;
    }

    .location-coord {
        font-size: 12px !important;
        color: #999;
        margin-top: 2px;
    }

    /* REFRESH */
    .location-refresh {
        border: none;
        background: transparent;
        padding: 4px;
        cursor: pointer;
        border-radius: 50%;
    }

    .location-refresh img {
        width: 18px;
        height: 18px;
    }

    .location-refresh:hover {
        background: rgba(0, 0, 0, .06);
    }

    /* ================= IN OUT ================= */
    .io {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 32px;
    }

    .io p {
        font-size: 24px !important;
    }

    .io span {
        font-size: 24px !important;
    }

    .io div {
        background: #f7f8ff;
        padding: 22px;
        border-radius: 18px;
        text-align: center;
    }

    /* ================= BUTTON ================= */
    .history {
        width: 100%;
        padding: 18px;
        border-radius: 18px;
        border: none;
        font-size: 18px !important;
        font-weight: 600;
        background: linear-gradient(90deg, #12124a, #1f1f73);
        color: #fff;
        cursor: pointer;
    }

    /* ================= CAMERA MODAL ================= */
    .camera-modal {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, .85);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }


    .camera-modal.active {
        display: flex;
    }

    .camera-box {
        position: relative;
        background: #111;
        padding: 20px;
        border-radius: 20px;
        width: 480px;
        max-width: 92vw;
        text-align: center;
    }

    .camera-box video,
    .camera-box img {
        width: 100%;
        border-radius: 14px;
    }

    .close-camera {
        position: absolute;
        top: 12px;
        right: 12px;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        border: none;
        background: rgba(255, 255, 255, .25);
        color: #fff;
        font-size: 22px !important;
        cursor: pointer;
    }

    .camera-actions {
        display: flex;
        gap: 12px;
        margin-top: 16px;
    }

    .camera-actions button {
        flex: 1;
        padding: 12px;
        border-radius: 12px;
        border: none;
        cursor: pointer;
    }

    .capture {
        background: #4b78ff;
        color: #fff;
    }

    .submit {
        background: #2ecc71;
        color: #fff;
        display: none;
    }

    .retake {
        background: #555;
        color: #fff;
        display: none;
    }

    .close-camera {
        position: absolute;
        top: 12px;
        right: 12px;

        width: 48px;
        /* ⬅️ LEBIH BESAR */
        height: 48px;
        border-radius: 50%;

        border: none;
        background: rgba(255, 255, 255, .25);
        color: #fff;

        font-size: 22px !important;
        cursor: pointer;

        display: flex;
        align-items: center;
        justify-content: center;

        z-index: 10;
        /* ⬅️ WAJIB */
    }

    /* css history */

    /* Modal background */
    .modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.4);
        z-index: 999;
    }

    .back-icon {
        width: 22px;
        height: 22px;
        cursor: pointer;
    }

    .back-icon:active {
        opacity: 0.6;
    }


    /* Modal box */
    .modal-content {
        background: #fff;
        width: 620px;
        max-width: 95%;
        margin: 40px auto;
        border-radius: 12px;
        overflow: hidden;
    }

    /* Header */
    .modal-header {
        padding: 15px;
        border-bottom: 1px solid #eee;
        display: flex;
        align-items: center;
        gap: 10px;
        background: linear-gradient(180deg, #6b6df7, #5f63f2);
    }

    .modal-header h3 {
        flex: 1;
        margin: 0;
    }

    .back {
        cursor: pointer;
        font-size: 18px;
    }

    .filter input {
        border: 1px solid #ddd;
        padding: 5px;
        border-radius: 6px;
    }

    /* List */
    .attendance-list {
        padding: 15px;
        max-height: 60vh;
        /* tinggi area scroll */
        overflow-y: auto;
    }

    .attendance-list {
        scrollbar-width: thin;
        /* Firefox */
        scrollbar-color: #ccc transparent;
    }

    .attendance-list::-webkit-scrollbar {
        width: 6px;
    }

    .attendance-list::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 10px;
    }

    .attendance-card {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px;
        border-radius: 12px;
        border: 1px solid #eee;
        margin-bottom: 12px;
    }

    .attendance-card {
        cursor: pointer;
        transition: background 0.2s;
    }

    .attendance-card:hover {
        background: #fafafa;
    }

    /* Date */
    .date {
        display: flex;
        gap: 10px;
    }

    .day {
        color: #666;
        font-size: 32px !important;
        font-weight: bold;
    }

    .month {
        font-size: 18px !important;
        color: #666;
    }

    /* Status */
    .status {
        display: flex;
        gap: 8px;
    }

    .badge {
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 18px !important;
        color: #fff;
        font-weight: bold;
    }

    .green {
        background: #4CAF50;
    }

    .red {
        background: #F44336;
    }

    .orange {
        background: #FF9800;
    }

    /* css modal detail */
    .detail-modal {
        background: rgba(0, 0, 0, 0.5);
        z-index: 1100;
    }

    .detail-content {
        width: 100%;
        max-width: 720px;
        margin: 40px auto;
        border-radius: 14px;
        background: #fff;
    }

    /* Date */
    .detail-date {
        text-align: center;
        font-weight: 600;
        padding: 10px 0;
        color: #333;
        font-size: 20px !important;
    }

    /* Cards wrapper */
    .detail-cards {
        display: flex;
        gap: 12px;
        padding: 15px;
    }

    /* Card */
    .detail-card {
        flex: 1;
        border: 1px solid #eaeaea;
        border-radius: 12px;
        overflow: hidden;
        background: #fff;
    }

    .detail-card img {
        width: 100%;
        height: 260px;
       object-fit: contain;
    }

    /* Info */
    .detail-info {
        padding: 10px;
        text-align: center;
        color: #777;
    }

    .detail-info small {
        display: block;
        font-size: 12px;
    }

    .detail-info strong {
        font-size: 18px;
        display: block;
        margin-top: 4px;
    }

    .status-text {
        font-size: 12px;
        color: #f5473b;
        margin-top: 4px;
    }

    /* Mobile */
    @media (max-width: 480px) {
        .detail-cards {
            flex-direction: column;
        }

        .detail-card img {
            height: 200px;
        }
    }
</style>


<div class="page">

    <div class="time">
        <h1 id="clock"></h1>
        <p id="date"></p>
    </div>

    <div class="card">

        <div class="header">
            <div class="subtitle">Schedule shift Today</div>
            <div class="shift-time"><?= $schedule ?></div>
            <div class="shift-type <?= $no_shift ?>"> <?= $shift ?> </div>
        </div>

        <div class="info">Selfie Photo is Required to Check In / Check Out</div>

        <div class="fingerprint-wrap">
            <div class="fingerprint" onclick="openCamera()">
                <img src="<?= base_url('assets/image/fingerprint-icon.png') ?>">
            </div>
        </div>

        <div class="location-pill">
            <img src="<?= base_url('assets/image/pin.png') ?>" class="location-icon">

            <div class="location-text" onclick="openGoogleMaps()">
                <div id="locationAddress">Mengambil lokasi...</div>
                <div class="location-coord" id="locationCoord"></div>
            </div>

            <button class="location-refresh" onclick="refreshLocation(event)">
                <img src="<?= base_url('assets/image/reload.png') ?>">
            </button>
        </div>

        <div class="io">
            <div>
                <p>In</p><span style="color: #4CAF50"><?= $time_in ?></span>
            </div>
            <div>
                <p>Out</p><span style="color: #FF9800;"><?= $time_out ?> </span>
            </div>
        </div>

        <button class="history" onclick=btnHistory()>History</button>
    </div>
</div>

<!-- CAMERA MODAL -->
<div class="camera-modal" id="cameraModal">
    <div class="camera-box">
        <button class="close-camera" onclick="closeCamera(event)">✕</button>
        <video id="video" autoplay playsinline></video>
        <img id="preview" style="display:none">

        <div class="camera-actions">
            <button class="capture" onclick="capturePhoto()">Ambil Foto</button>
            <button class="submit" onclick="submitAttendance()" style="display:none">Submit</button>
            <button class="retake" onclick="retakePhoto()" style="display:none">Ulangi</button>
        </div>
    </div>
</div>

<div id="attendanceModal" class="modal">
    <div class="modal-content">
        <!-- Header -->
        <div class="modal-header">
            <img src="<?= base_url('assets/image/icon_back.png') ?>"
                class="back-icon"
                onclick="closeHistory()"
                alt="Back">
            <h3>Attendance</h3>

            <div class="filter">
                <input type="month" id="filterMonth">
            </div>
        </div>

        <!-- List -->
        <div class="attendance-list">
        </div>
    </div>
</div>

<div id="detailModal" class="modal detail-modal">
    <div class="modal-content detail-content">

        <!-- Header -->
        <div class="modal-header">
            <img src="<?= base_url('assets/image/icon_back.png') ?>"
                class="back-icon"
                onclick="closeDetail()"
                alt="Back">
            <h3>Attendance Detail</h3>
        </div>

        <!-- Date -->
        <div class="detail-date" id="detailDate">
        </div>

        <!-- Cards -->
        <div class="detail-cards">

            <!-- IN -->
            <div class="detail-card">
                <img id="imgIn" src="" alt="Check In">
                <div class="detail-info">
                    <p style="font-size: 18px !important; font-size:20px;">In</p>
                    <span id="timeIn" style="color: #4CAF50; font-size:20px !important;"></span>
                    <div class="status-text" id="statusIn">
                    </div>
                </div>
            </div>

            <!-- OUT -->
            <div class="detail-card">
                <img id="imgOut" src="" alt="Check Out">
                <div class="detail-info">
                    <p style="font-size: 18px !important;">Out</p>
                    <span id="timeOut" style="color: #FF9800; font-size:20px !important;"></span>
                    <div class="status-text" id="statusOut">
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>





<script>
    let stream = null;
    let photoData = null;
    let userLocation = null;
    const today = new Date().toISOString().slice(0, 10);
    const now = new Date().toTimeString().slice(0, 8);
    let time_in = '<?= $time_in ?>';
    let time_out = '<?= $time_out ?>';
    let date_in = '<?= $date_in ?>';
    let date_out = '<?= $date_out ?>';

    if (date_in && time_in) {
        date_out = today;
        time_out = now;
    } else {
        date_in = today;
        time_in = now;
    }

    /* ===== CLOCK ===== */
    setInterval(() => {
        const now = new Date();
        clock.innerText = now.toLocaleTimeString("en-GB");
        date.innerText = now.toLocaleDateString("en-US", {
            weekday: "long",
            year: "numeric",
            month: "long",
            day: "numeric"
        });
    }, 1000);

    /* ===== CAMERA ===== */
    async function openCamera() {

        // 1️⃣ cek lokasi di frontend
        if (!userLocation || !userLocation.lat || !userLocation.lng) {
            $.messager.alert(
                "Warning",
                "Location is not available. Please refresh your location."
            );
            return;
        }

        try {
            // 2️⃣ kirim ke backend (CI3 POST)
            const formData = new FormData();
            formData.append("latitude", userLocation.lat);
            formData.append("longitude", userLocation.lng);

            const res = await fetch(
                "<?= base_url('attandance/live_attandances/checklocationattendance') ?>", {
                    method: "POST",
                    body: formData
                }
            );

            const result = await res.json();

            // 3️⃣ JIKA LOKASI VALID (MASUK RADIUS)
            if (result.theme === "success") {
                userLocation.company_code = result.data.company_code;
                return openCameraStream(); // langsung buka kamera
            }

            // 4️⃣ JIKA DI LUAR RADIUS → CONFIRM
            $.messager.confirm({
                title: "Warning",
                msg: result.message + "<br><br>Apakah Anda yakin ingin melanjutkan absensi di lokasi ini?",
                width: 480, // ⬅️ BESAR
                height: "auto",
                fn: function(r) {
                    if (r) {
                        userLocation.outside_radius = true;
                        userLocation.company_code = '';
                        openCameraStream();
                    }
                }
            });


        } catch (err) {
            console.error(err);
            $.messager.alert(
                "Error",
                "Unable to validate location or access camera."
            );
        }
    }

    async function openCameraStream() {
        try {
            stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: "user"
                },
                audio: false
            });

            video.srcObject = stream;
            cameraModal.classList.add("active");

        } catch (err) {
            console.error(err);
            $.messager.alert(
                "Error",
                "Camera cannot be accessed."
            );
        }
    }

    function dataURLtoBlob(dataURL) {
        const arr = dataURL.split(',');
        const mime = arr[0].match(/:(.*?);/)[1];
        const bstr = atob(arr[1]);
        let n = bstr.length;
        const u8arr = new Uint8Array(n);

        while (n--) {
            u8arr[n] = bstr.charCodeAt(n);
        }

        return new Blob([u8arr], {
            type: mime
        });
    }

    function capturePhoto() {
        const canvas = document.createElement("canvas");
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext("2d").drawImage(video, 0, 0);

        photoData = canvas.toDataURL("image/jpeg");

        preview.src = photoData;
        preview.style.display = "block";
        video.style.display = "none";

        document.querySelector(".capture").style.display = "none";
        document.querySelector(".submit").style.display = "block";
        document.querySelector(".retake").style.display = "block";

        stopCamera();
    }

    function retakePhoto() {
        resetCameraUI();
        openCameraStream();
    }

    function closeCamera(e) {
        if (e) e.stopPropagation();
        stopCamera();
        resetCameraUI();
        cameraModal.classList.remove("active");
    }

    function stopCamera() {
        if (stream) {
            stream.getTracks().forEach(t => t.stop());
            stream = null;
        }
    }

    function resetCameraUI() {
        preview.style.display = "none";
        video.style.display = "block";
        document.querySelector(".capture").style.display = "block";
        document.querySelector(".submit").style.display = "none";
        document.querySelector(".retake").style.display = "none";
    }

    /* ===== LOCATION ===== */
    function getLocation() {
        if (!navigator.geolocation) return;

        navigator.geolocation.getCurrentPosition(
            async pos => {
                    userLocation = {
                        lat: pos.coords.latitude,
                        lng: pos.coords.longitude
                    };
                    await reverseGeocode(userLocation.lat, userLocation.lng);
                },
                () => updateLocation("Lokasi tidak tersedia")
        );
    }

    async function reverseGeocode(lat, lng) {
        const res = await fetch(
            `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`
        );
        const data = await res.json();
        updateLocation(data.display_name, lat, lng);
    }

    function updateLocation(address, lat = "", lng = "") {
        locationAddress.innerText = address;
        locationCoord.innerText = lat && lng ? `${lat}, ${lng}` : "";
    }

    function refreshLocation(e) {
        e.stopPropagation();
        updateLocation("Mengambil lokasi...");
        getLocation();
    }

    function openGoogleMaps() {
        if (!userLocation) return;
        window.open(
            `https://www.google.com/maps?q=${userLocation.lat},${userLocation.lng}`,
            "_blank"
        );
    }

    /* INIT */
    getLocation();

    async function submitAttendance() {

        if (!photoData || !userLocation) {
            alert("Attendance data is incomplete.");
            return;
        }

        const blob = dataURLtoBlob(photoData);
        const file = new File([blob], "attendance.jpg", {
            type: "image/jpeg"
        });

        const formData = new FormData();

        formData.append("foto", file);
        formData.append("latitude", userLocation.lat);
        formData.append("longitude", userLocation.lng);

        formData.append("outside_radius", userLocation.outside_radius ? 1 : 0);
        formData.append("accuracy", userLocation.accuracy || "");

        formData.append("time_in", time_in || "");
        formData.append("time_out", time_out || "");
        formData.append("date_in", date_in || "");
        formData.append("date_out", date_out || "");

        formData.append("position", userLocation.company_code || "");

        try {
            const res = await fetch(
                "<?= base_url('attandance/live_attandances/checkin') ?>", {
                    method: "POST",
                    body: formData // ⬅️ JANGAN set Content-Type
                }
            );

            const result = await res.json();

            if (result.theme !== "success") {
                throw new Error(result.message || "Failed");
            }

            $.messager.alert("Success", result.message);
            closeCamera();
            location.reload();

        } catch (err) {
            console.error(err);
            $.messager.alert("Error", "Failed to submit attendance.");
        }

    }

    function btnHistory() {
        document.getElementById('attendanceModal').style.display = 'block';

        // set default bulan sekarang
        const now = new Date();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const year = now.getFullYear();

        const currentMonth = `${year}-${month}`;
        document.getElementById('filterMonth').value = currentMonth;

        loadAttendance(currentMonth);
    }

    function loadAttendance(month) {
        const formData = new FormData();
        formData.append("month", month);
        fetch('<?= base_url('attandance/live_attandances/history') ?>', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => renderAttendance(data))
            .catch(err => console.error(err));
    }

    function closeHistory() {
        document.getElementById('attendanceModal').style.display = 'none';
    }

    function myformatter(date) {
        var y = date.getFullYear();
        var m = date.getMonth() + 1;
        var d = date.getDate();
        return y + '-' + (m < 10 ? ('0' + m) : m) + '-' + (d < 10 ? ('0' + d) : d);
    }

    //Format Datepicker
    function myparser(s) {
        if (!s) return new Date();
        var ss = (s.split('-'));
        var y = parseInt(ss[0], 10);
        var m = parseInt(ss[1], 10);
        var d = parseInt(ss[2], 10);
        if (!isNaN(y) && !isNaN(m) && !isNaN(d)) {
            return new Date(y, m - 1, d);
        } else {
            return new Date();
        }
    }

    function openDetail(date, timeIn, timeOut, photoIn, photoOut, statusIn, statusOut) {
        document.getElementById('detailModal').style.display = 'block';

        // Format date ke tampilan
        const d = new Date(date + 'T00:00:00');
        const displayDate = d.toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });

        document.getElementById('detailDate').innerText = displayDate;

        // Time
        document.getElementById('timeIn').innerText = timeIn || '-';
        document.getElementById('timeOut').innerText = timeOut || '-';

        // Images (DINAMIS)
        document.getElementById('imgIn').src =
            buildAttendanceImage(date, photoIn);

        document.getElementById('imgOut').src =
            buildAttendanceImage(date, photoOut);

        // Status
        if (statusIn == 0) {
            statusIn = "";
        } else if (statusIn == 1) {
            statusIn = "Waiting Approval";
        } else {
            statusIn = "Rejected. Please contact HR.";
        }

        if (statusOut == 0) {
            statusOut = "";
        } else if (statusOut == 1) {
            statusOut = "Waiting Approval";
        } else {
            statusOut = "Rejected. Please contact HR.";
        }

        document.getElementById('statusIn').innerText = statusIn || '';
        document.getElementById('statusOut').innerText = statusOut || '';
    }


    function closeDetail() {
        document.getElementById('detailModal').style.display = 'none';
    }

    document.getElementById('filterMonth').addEventListener('change', function() {
        loadAttendance(this.value);
    });

    function renderAttendance(rows) {
        const list = document.querySelector('.attendance-list');
        list.innerHTML = '';

        if (!rows.length) {
            list.innerHTML = '<p style="text-align:center;color:#999">No data</p>';
            return;
        }

        rows.forEach(r => {
            const d = new Date(r.date);
            const day = d.getDate();
            const month = d.toLocaleDateString('en-US', {
                    month: 'long'
                }) +
                '<br>' +
                d.getFullYear();

            if (r.status_in == 0) {
                status_in = "green";
            } else if (r.status_in == 1) {
                status_in = "orange";
            } else {
                status_in = "red";
            }

            if (r.status_out == 0) {
                status_out = "green";
            } else if (r.status_out == 1) {
                status_out = "orange";
            } else {
                status_out = "red";
            }


            list.innerHTML += `
                <div class="attendance-card"
                    onclick="openDetail('${r.date}','${r.in}','${r.out}', '${r.foto_in}', '${r.foto_out}', '${r.status_in}', '${r.status_out}')">
                    <div class="date">
                    <div class="day">${day}</div>
                    <div class="month">${month}</div>
                    </div>
                    <div class="status">
                    <span class="badge ${status_in}">IN</span>
                    <span class="badge ${status_out}">OUT</span>
                    </div>
                </div>
                `;
        });
    }

    function buildAttendanceImage(date, filename) {
        if (!filename) return `<?= base_url('assets/image/no-photo.png')  ?>`;

        // date: YYYY-MM-DD → YYYYMM
        const [y, m] = date.split('-');
        const folder = y + m;

        return `<?= base_url('assets/document/attendance/${folder}/${filename}')  ?>`;
    }
</script>