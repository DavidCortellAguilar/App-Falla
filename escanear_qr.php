<?php
require_once __DIR__ . '/config.php';
require_admin();
$page_title = 'Escanear QR';
include __DIR__ . '/header.php';
include __DIR__ . '/sidebar.php';
?>
<main class="main-dashboard">
    <header class="dashboard-topbar">
        <button class="mobile-menu-btn" type="button" onclick="toggleSidebar()">☰</button>
        <div>
            <h1>Escanear QR</h1>
            <p>Escanea el QR de una reserva pagada</p>
        </div>
        <div class="topbar-actions">
            <a href="reservas.php" class="topbar-btn">← Reservas</a>
        </div>
    </header>

    <section class="dashboard-content">
        <div class="card-modern qr-scan-card">
            <div id="reader" class="qr-reader-box"></div>
            <div id="scanStatus" class="text-muted mt-3">La cámara se abrirá al permitir el acceso.</div>
            <button id="btnReanudar" class="btn btn-primary mt-3" type="button" style="display:none;" onclick="reanudarEscaneo()">Escanear otro QR</button>
            <p class="text-muted mt-3">Nota: el permiso de cámara lo controla iPhone/Safari. Esta pantalla mantiene el escáner abierto para poder leer varios QR seguidos sin volver a cargar la página.</p>
        </div>

        <div id="resultadoQR" class="card-modern qr-result" style="display:none;"></div>
    </section>
</main>

<style>
.qr-reader-box{max-width:420px;width:100%;overflow:hidden;border-radius:18px}
.qr-result.valido{border:4px solid #22c55e;background:#f0fdf4}
.qr-result.usado,.qr-result.invalido,.qr-result.error{border:4px solid #ef4444;background:#fff1f2}
.qr-result h2{font-size:clamp(28px,5vw,54px);margin-bottom:18px;text-align:center}
.qr-result p{text-align:center;font-size:20px}
.qr-detail-list{margin-top:24px}
.qr-detail-list div{padding:16px 0;border-top:1px solid rgba(15,23,42,.12);text-align:center}
.qr-detail-list span{display:block;text-transform:uppercase;letter-spacing:.14em;color:#667085;font-weight:800;margin-bottom:8px}
.qr-detail-list strong{font-size:22px;color:#101828}
</style>

<script src="https://unpkg.com/html5-qrcode"></script>
<script>
let qrScanner = null;
let escaneando = false;
let bloqueado = false;

function extraerToken(texto) {
    try {
        const url = new URL(texto);
        return url.searchParams.get('t') || '';
    } catch (e) {
        return texto.trim();
    }
}

function pintarResultado(data) {
    const box = document.getElementById('resultadoQR');
    const clase = data.resultado || 'error';
    const icono = clase === 'valido' ? '✅' : '❌';
    let html = `<h2>${icono} ${clase === 'valido' ? 'QR válido' : (clase === 'usado' ? 'QR ya usado' : 'QR no válido')}</h2>`;
    html += `<p>${data.mensaje || 'No se ha podido validar el QR.'}</p>`;
    if (data.reserva) {
        html += `<div class="qr-detail-list">
            <div><span>Acto</span><strong>${escapeHtml(data.reserva.acto)}</strong></div>
            <div><span>Fecha</span><strong>${escapeHtml(data.reserva.fecha)}</strong></div>
            <div><span>Fallero/a</span><strong>${escapeHtml(data.reserva.fallero)}</strong></div>
            <div><span>Opción</span><strong>${escapeHtml(data.reserva.opcion)}</strong></div>
            <div><span>Invitado</span><strong>${escapeHtml(data.reserva.invitado)}</strong></div>
        </div>`;
    }
    html += `<div class="text-center mt-4"><button class="btn btn-primary" type="button" onclick="reanudarEscaneo()">Escanear otro QR</button></div>`;
    box.className = 'card-modern qr-result ' + clase;
    box.innerHTML = html;
    box.style.display = 'block';
    box.scrollIntoView({behavior:'smooth', block:'start'});
}

function escapeHtml(str) {
    return String(str || '').replace(/[&<>'"]/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#039;','"':'&quot;'}[c]));
}

async function validarTextoQR(texto) {
    if (bloqueado) return;
    bloqueado = true;
    const token = extraerToken(texto);
    document.getElementById('scanStatus').textContent = 'QR leído. Validando...';
    document.getElementById('btnReanudar').style.display = 'none';

    try {
        if (qrScanner && escaneando) {
            await qrScanner.pause(true);
        }
        const resp = await fetch('validar_qr_ajax.php?t=' + encodeURIComponent(token), {headers:{'Accept':'application/json'}});
        const data = await resp.json();
        pintarResultado(data);
        document.getElementById('scanStatus').textContent = 'Validación realizada.';
        document.getElementById('btnReanudar').style.display = 'inline-block';
    } catch (e) {
        pintarResultado({resultado:'error', mensaje:'No se ha podido validar el QR. Revisa la conexión e inténtalo de nuevo.'});
        document.getElementById('btnReanudar').style.display = 'inline-block';
    }
}

async function iniciarEscaner() {
    if (!window.Html5Qrcode) {
        document.getElementById('scanStatus').textContent = 'No se ha podido cargar el lector QR.';
        return;
    }
    qrScanner = new Html5Qrcode('reader');
    try {
        await qrScanner.start({facingMode:'environment'}, {fps:10, qrbox:250}, validarTextoQR);
        escaneando = true;
        localStorage.setItem('fssaf_camera_allowed', '1');
        document.getElementById('scanStatus').textContent = 'Escáner activo. Apunta al QR.';
    } catch (e) {
        document.getElementById('scanStatus').textContent = 'No se ha podido abrir la cámara. Puedes escanear el QR con la cámara normal del móvil y abrir el enlace.';
    }
}

async function reanudarEscaneo() {
    bloqueado = false;
    document.getElementById('resultadoQR').style.display = 'none';
    document.getElementById('btnReanudar').style.display = 'none';
    document.getElementById('scanStatus').textContent = 'Escáner activo. Apunta al siguiente QR.';
    try {
        if (qrScanner && escaneando) {
            await qrScanner.resume();
        } else {
            await iniciarEscaner();
        }
    } catch(e) {
        await iniciarEscaner();
    }
}

document.addEventListener('DOMContentLoaded', iniciarEscaner);
</script>
<?php include __DIR__ . '/footer.php'; ?>
