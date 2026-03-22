<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class SiteFeuilleDatesPublication extends AbstractMigration
{
    public function change(): void
    {
        $this->query('ALTER TABLE afup_site_feuille ADD date_debut_publication int(11) DEFAULT NULL AFTER date, ADD date_fin_publication int(11) DEFAULT NULL AFTER date_debut_publication');
    }
}
