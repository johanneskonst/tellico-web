                    <h1>Contents of &ldquo;<?php echo $this->datafile->getCollection()->getTitle(); ?>&rdquo;</h1>
                    <pre><?php var_dump($this->datafile->getCollection()->getFieldCategories(), $this->datafile->getCollection()->getGroupFields()); ?></pre>
                    <!-- <pre><?php var_dump($this->datafile->getCollection()); ?></pre> -->
<?php foreach ($this->datafile->getCollection()->getEntries() as $entry) {
    echo '<p>', $entry->getId(), ' - ', $entry->getTitle(), '</p>';
}