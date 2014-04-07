<!-- FOOTER ******************************************************************* -->
<!-- ************************************************************************** --> 
<div id="footer">
    <!-- <h2><a name="footermarke" id="footermarke">Statusinformationen zur Seite</a></h2>
    <p>
      Letzte &Auml;nderung:  
    <?php the_modified_date('H:m j. M Y'); ?>
    </p> -->
    <div id="footerinfos">
        <div id="tecmenu">
            <h2 class="u"><a name="hilfemarke" id="hilfemarke">Technisches Menu</a></h2>		
            <ul>
                <li class="first"><a href="/">Blogs@FAU</a></li>
                <li><a href="http://www.portal.uni-erlangen.de/forums/viewforum/94">Forum</a></li>
                <li><a href="/hilfe/">Hilfe</a></li>
                <li><a href="<?php bloginfo('url'); ?>/kontakt/">Kontakt</a></li>
                <li><a href="/impressum/">Impressum</a></li>
                <li class="last"><a href="/nutzungsbedingungen/">Nutzungsbedingungen</a></li>
            </ul>
        </div>        
        <!-- ZUSATZINFO *************************************************************** -->
        <!-- ************************************************************************** -->
        <div id="zusatzinfo" class="noprint">
            <a name="zusatzinfomarke" id="zusatzinfomarke"></a>

            <?php if ( ! dynamic_sidebar( 4 ) ): ?>

                <!--
                        In dieser Box k&ouml;nnten hilfreiche Links oder sonstige Informationen stehen, 
                        welche auf jeder Seite eingeblendet werden sollen.
                        Diese Angaben werden bei der Ausgabe auf dem Drucker nicht mit ausgegeben!

                -->

                <!--  <h2>Zusatzinfo</h2>   -->
            <?php endif; ?>

            <p class="skip">
                <a href="#seitenmarke">Zum Seitenanfang</a>
            </p>
        </div>

    </div> <!-- /footerinfos -->	
</div>
</div><!-- /seite -->
<?php wp_footer(); ?>
</body>
</html>
