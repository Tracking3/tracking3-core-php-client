<?php

declare(strict_types=1);

namespace Tracking3\Core\Client\Project;

use Doctrine\Common\Collections\ArrayCollection;

class ProjectList extends ArrayCollection
{
    public function __construct(
        array $projects = []
    ) {
        $elements = [];

        foreach ($projects as $project) {
            $elements[] = new Project($project);
        }

        parent::__construct($elements);
    }
}
