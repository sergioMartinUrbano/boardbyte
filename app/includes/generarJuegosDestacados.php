<?php
foreach ($productos as $game) {
    echo '<div class="game-card">';
    echo '<img src="/boardbyte/resources/images/productos/portadas/' . $game->getFotoPortada() . '">';
    echo '<div class="game-card-content">';
    echo '<h3>' . $game->getNombre() . '</h3>';
    echo '<p>' . $game->getDescripcionCorta() . '</p>';
    echo '</div>';
    echo '</div>';
}
?>