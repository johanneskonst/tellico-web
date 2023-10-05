                    <p class="small-footnote">Rendered in <?php printf('%0.2f', (microtime(true) - T) * 1000); ?> ms (or <?php echo number_format(microtime(true) - T, 3); ?> seconds) using <?php printf('%0.1f', (memory_get_peak_usage() / (1024 * 1024))); ?> Mb of memory.</p>
                </article>
            </section>
        </main>
    </body>
</html>