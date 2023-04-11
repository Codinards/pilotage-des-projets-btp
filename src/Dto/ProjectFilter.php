<?php

namespace App\Dto;

use App\Entity\Company;
use App\Entity\ProjectType;
use App\Entity\Sector;
use DateTime;

class ProjectFilter
{
    public ?ProjectType $type = null;

    public bool $typeOr = false;

    public ?Company $company = null;

    public bool $companyOr = false;

    public ?Sector $sector = null;

    public bool $sectorOr = false;

    public ?DateTime $startBefore = null;

    public bool $startBeforeOr = false;

    public ?DateTime $startAfter = null;

    public bool $startAfterOr = false;

    public ?DateTime $endBefore = null;

    public bool $endBeforeOr = false;

    public ?DateTime $endAfter = null;

    public bool $endAfterOr = false;

    public ?int $amountLessThan = null;

    public bool $amountLessThanOr = false;

    public ?int $amountMoreThan = null;

    public bool $amountMoreThanOr = false;

    public array $params = [];

    public array $and = [];

    public array $or = [];


    public function buildQuery(string $alias): string
    {
        $sql = '';
        if ($this->type) {
            $sql = $alias . '.type = :type';
            $this->params['type'] = $this->type;
            $this->and[] = $sql;
        }
        if ($this->sector) {
            $localSql = $alias . '.sector = :sector';
            $this->params['sector'] = $this->sector;
            if (empty($sql)) {
                $sql = $localSql;
                $this->and[] = $sql;
            } else {
                $this->sectorOr == false ?  $this->and[] = $localSql : $this->or[] = $localSql;
            }
        }
        if ($this->company) {
            $localSql = $alias . '.company = :company';
            $this->params['company'] = $this->company;
            if (empty($sql)) {
                $sql = $localSql;
                $this->and[] = $sql;
            } else {

                $this->companyOr == false ?  $this->and[] = $localSql : $this->or[] = $localSql;
            }
        }
        if ($this->amountLessThan) {
            $localSql = $alias . '.cost >= :costLess';
            $this->params['costLess'] = $this->amountLessThan;
            if (empty($sql)) {
                $sql = $localSql;
                $this->and[] = $sql;
            } else {
                $this->amountLessThanOr == false ?  $this->and[] = $localSql : $this->or[] = $localSql;
            }
        }

        if ($this->amountMoreThan) {
            $localSql = $alias . '.cost <= :costMore';
            $this->params['costMore'] = $this->amountMoreThan;
            if (empty($sql)) {
                $sql = $localSql;
                $this->and[] = $sql;
            } else {
                $this->amountMoreThanOr == false ?  $this->and[] = $localSql : $this->or[] = $localSql;
            }
        }

        if ($this->startBefore) {
            $localSql = $alias . '.startAt <= :startBefore';
            $this->params['startBefore'] = $this->startBefore;
            if (empty($sql)) {
                $sql = $localSql;
                $this->and[] = $sql;
            } else {
                $this->startBeforeOr == false ?  $this->and[] = $localSql : $this->or[] = $localSql;
            }
        }

        if ($this->startAfter) {
            $localSql = $alias . '.startAt >= :startAfter';
            $this->params['startAfter'] = $this->startAfter;
            if (empty($sql)) {
                $sql = $localSql;
                $this->and[] = $sql;
            } else {
                $this->startAfterOr == false ?  $this->and[] = $localSql : $this->or[] = $localSql;
            }
        }

        if ($this->endBefore) {
            $localSql = $alias . '.endAt <= :endBefore';
            $this->params['endBefore'] = $this->endBefore;
            if (empty($sql)) {
                $sql = $localSql;
                $this->and[] = $sql;
            } else {
                $this->endBeforeOr == false ?  $this->and[] = $localSql : $this->or[] = $localSql;
            }
        }

        if ($this->endAfter) {
            $localSql = $alias . '.endAt <= :endAfter';
            $this->params['endAfter'] = $this->endAfter;
            if (empty($sql)) {
                $sql = $localSql;
                $this->and[] = $sql;
            } else {
                $this->endAfterOr == false ?  $this->and[] = $localSql : $this->or[] = $localSql;
            }
        }

        $sql = join(' AND ', $this->and);
        if (!empty($this->or)) {
            $sql = "(" . $sql . ") OR (" . join(' OR ', $this->or) . ")";
        }

        return $sql;
    }
}
