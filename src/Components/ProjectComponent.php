<?php

namespace App\Components;

use App\Repository\CompanyRepository;
use App\Repository\ProjectRepository;
use App\Repository\ProjectTypeRepository;
use App\Repository\SectorRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;

#[AsLiveComponent('project')]
class ProjectComponent
{

    /**
     * @var Project[]
     */
    public ?array $projects =  null;

    #[LiveProp(true)]
    public int $count = 0;

    #[LiveProp(true)]
    public ?int $checked = null;

    #[LiveProp(true)]
    public string $periodDirection = '>=';

    #[LiveProp(true)]
    public string $orderDirection = 'ASC';

    #[LiveProp(true)]
    public string $orderField = 'number';

    #[LiveProp(true)]
    public string $search = '';

    public array $types = [];

    #[LiveProp(true)]
    public ?int $type = null;

    public array $sectors = [];

    #[LiveProp(true)]
    public ?int $sector = null;

    public array $companies = [];

    #[LiveProp(true)]
    public ?int $company = null;

    #[LiveProp(true)]
    public ?int $cost = null;

    #[LiveProp(true)]
    public string $costDirection = ">=";

    #[LiveProp(true)]
    public ?string $startAt = null;

    #[LiveProp(true)]
    public string $startAtDirection = ">=";

    #[LiveProp(true)]
    public ?string $endAt = null;

    #[LiveProp(true)]
    public string $endAtDirection = ">=";

    #[LiveProp(true)]
    public ?string $presentedAt = null;

    #[LiveProp(true)]
    public string $presentedAtDirection = ">=";

    public function __construct(
        private ProjectRepository $projectRepository,
        private ProjectTypeRepository $typeRepository,
        private SectorRepository $sectorRepository,
        private CompanyRepository $companyRepository,
    ) {
        $this->getProjects();
        if (empty($this->types)) {
            $this->types = $typeRepository->findAll();
        }

        if (empty($this->sectors)) {
            $this->sectors = $sectorRepository->findAll();
        }

        if (empty($this->companies)) {
            $this->companies = $companyRepository->findAll();
        }
        $this->getCount();
    }

    public function __invoke()
    {
    }

    private function getCount()
    {
        $this->count = count($this->projects);
    }

    private function fillProjects()
    {
        $this->getProjects();
        $this->getCount();
    }

    #[LiveAction]
    public function typeFilter()
    {
        foreach ($this->types as $type) {
            if ($type->getId() === $this->type) {
                $type = $type;
                break;
            }
        }
        $this->fillProjects();

        return $this->type;
    }

    #[LiveAction]
    public function sectorFilter()
    {
        foreach ($this->sectors as $sector) {
            if ($sector->getId() === $this->sector) {
                $sector = $sector;
                break;
            }
        }
        $this->fillProjects();

        return $this->sector;
    }

    #[LiveAction]
    public function companyFilter()
    {
        foreach ($this->companies as $company) {
            if ($company->getId() === $this->company) {
                $company = $company;
                break;
            }
        }
        $this->fillProjects();

        return $this->company;
    }

    #[LiveAction]
    public function costFilter()
    {
        $this->fillProjects();

        return $this->cost;
    }

    #[LiveAction]
    public function toggleCostDirection()
    {
        if ($this->costDirection === '<=') {
            $this->costDirection = ">=";
        } else {
            $this->costDirection = "<=";
        }
        if ($this->cost ?? null) {
            $this->getProjects();
        }
        $this->getCount();
        return $this->costDirection;
    }

    #[LiveAction]
    public function startAtFilter()
    {
        $this->fillProjects();
        return $this->startAt;
    }


    #[LiveAction]
    public function toggleStartAtDirection()
    {
        if ($this->startAtDirection === '<=') {
            $this->startAtDirection = ">=";
        } else {
            $this->startAtDirection = "<=";
        }
        if ($this->startAtDirection ?? null) {
            $this->getProjects();
        }
        $this->getCount();

        return $this->startAtDirection;
    }


    #[LiveAction]
    public function endAtFilter()
    {
        $this->fillProjects();

        return $this->endAt;
    }


    #[LiveAction]
    public function toggleEndAtDirection()
    {
        if ($this->endAtDirection === '<=') {
            $this->endAtDirection = ">=";
        } else {
            $this->endAtDirection = "<=";
        }
        if ($this->endAtDirection ?? null) {
            $this->getProjects();
        }
        $this->getCount();

        return $this->endAtDirection;
    }

    #[LiveAction]
    public function presentedAtFilter()
    {
        $this->fillProjects();
        return $this->presentedAt;
    }


    #[LiveAction]
    public function togglePresentedAtDirection()
    {
        if ($this->presentedAtDirection === '<=') {
            $this->presentedAtDirection = ">=";
        } else {
            $this->presentedAtDirection = "<=";
        }
        if ($this->presentedAtDirection ?? null) {
            $this->getProjects();
        }
        $this->getCount();

        return $this->presentedAtDirection;
    }

    private function getProjects(): array
    {
        $criteria = [];
        if ($this->search !==  null) {
            $criteria['name'] = $this->search;
        }
        if (!empty($this->type)) {
            $criteria['type'] = $this->type;
        }
        if (!empty($this->cost)) {
            $criteria['cost'] = [$this->cost, $this->costDirection];
        }
        if (!empty($this->sector)) {
            $criteria['sector'] = $this->sector;
        }
        if (!empty($this->company)) {
            $criteria['company'] = $this->company;
        }
        if ($this->startAt ?? null) {
            $criteria['startAt'] = [new \DateTime($this->startAt), $this->startAtDirection];
        }
        if ($this->endAt ?? null) {
            $criteria['endAt'] = [new \DateTime($this->endAt), $this->endAtDirection];
        }
        if ($this->presentedAt ?? null) {
            $criteria['presentedAt'] = [new \DateTime($this->presentedAt), $this->presentedAtDirection];
        }

        $this->projects = $this->projectRepository->findByConditions($criteria, [$this->orderField, $this->orderDirection]);

        return $this->projects;
    }

    #[LiveAction]
    public function setHeaderName(#[LiveArg('name')] string $name)
    {
        if ($this->orderField === $name) {
            $this->orderDirection = $this->orderDirection === 'ASC' ? 'DESC' : 'ASC';
        } else {
            $this->orderField = $name;
            $this->orderDirection = 'ASC';
        }
        $this->fillProjects();
        return $this->orderField;
    }

    #[LiveAction]
    public function search()
    {
        $this->fillProjects();
        return $this->search;
    }
}
