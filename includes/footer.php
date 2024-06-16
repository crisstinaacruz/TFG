<?php
function getFooterHTML() {
    return '
    <footer class="footer">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-6 col-sm-4 col-md-3">
                    <h6 class="footer__title">Sobre nosotros</h6>
                    <ul class="footer__list">
                        <li><a href="../views/html/QuienesSomos.html">Quiénes somos</a></li>
                    </ul>
                </div>

                <div class="col-6 col-sm-4 col-md-3">
                    <h6 class="footer__title">Legal</h6>
                    <ul class="footer__list">
                        <li><a href="../views/html/AvisLegal.html">Aviso Legal</a></li>
                        <li><a href="../views/html/CondicionesCompra.html">Condiciones de compra</a></li>
                        <li><a href="../views/html/politicas.html">Políticas de privacidad</a></li>
                    </ul>
                </div>

                <div class="col-12 col-sm-4 col-md-3">
                    <h6 class="footer__title">Contacto</h6>
                    <ul class="footer__list">
                        <li><a href="tel:+34624233403">+34 624 23 34 03</a></li>
                        <li><a href="mailto:atencionalclient@cinemmagic.com">atencionalcliente@magiccinema.es</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>';
}

?>
