<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Service;

use App\Models\Emailtpl;

class EmailtplService
{
    /**
     * 通过邮件标识获得邮件模板
     *
     * @param  string  $token  邮件标识
     *
     * @author    assimon<ashang@utf8.hk>
     * @copyright assimon<ashang@utf8.hk>
     *
     * @link      http://utf8.hk/
     */
    public function detailByToken(string $token): Emailtpl
    {
        $tpl = Emailtpl::query()->where('tpl_token', $token)->first();

        return $tpl;
    }
}
