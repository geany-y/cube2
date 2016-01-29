<?php
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2014 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

require_once CLASS_REALDIR . 'pages/LC_Page.php';

class LC_Page_Ex extends LC_Page
{
    /**
     * Page のレスポンス送信.
     *
     * @return void
     */
    public function sendResponse()
    {
        // ループ防止に現在URLを格納
        $location = '';
        $netUrl = new Net_URL();
        $location = $netUrl->getUrl();

        // ログインされていなく、リダイレクト処理
        if (empty($this->isLogin) && !preg_match('/^.*original.*/', $location)) {
            $this->objDisplay->response->sendRedirect('original');
        }
        $objPlugin = SC_Helper_Plugin_Ex::getSingletonInstance($this->plugin_activate_flg);
        // ローカルフックポイントを実行.
        $this->doLocalHookpointAfter($objPlugin);

        // HeadNaviにpluginテンプレートを追加する.
        $objPlugin->setHeadNaviBlocs($this->arrPageLayout['HeadNavi']);

        // スーパーフックポイントを実行.
        $objPlugin->doAction('LC_Page_process', array($this));

        // ページクラス名をテンプレートに渡す
        $arrBacktrace = debug_backtrace();
        if (strlen($this->tpl_page_class_name) === 0) {
            $this->tpl_page_class_name = preg_replace('/_Ex$/', '', $arrBacktrace[1]['class']);
        }

        $this->objDisplay->prepare($this);
        $this->objDisplay->addHeader('Vary', 'User-Agent');
        $this->objDisplay->response->write();
    }
}
