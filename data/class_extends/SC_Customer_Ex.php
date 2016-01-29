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

require_once CLASS_REALDIR . 'SC_Customer.php';

class SC_Customer_Ex extends SC_Customer
{
    // ログインに成功しているか判定する。
    public function isLoginSuccess($dont_check_email_mobile = false)
    {
        // ログイン時のメールアドレスとDBのメールアドレスが一致している場合
        if (isset($_SESSION['customer']['customer_id'])
            && SC_Utils_Ex::sfIsInt($_SESSION['customer']['customer_id'])
        ) {
            $objQuery =& SC_Query_Ex::getSingletonInstance();
            $email = $objQuery->get('email', 'dtb_customer', 'customer_id = ?', array($_SESSION['customer']['customer_id']));
            if ($email == $_SESSION['customer']['email']) {
                // モバイルサイトの場合は携帯のメールアドレスが登録されていることもチェックする。
                // ただし $dont_check_email_mobile が true の場合はチェックしない。
                if (SC_Display_Ex::detectDevice() == DEVICE_TYPE_MOBILE && !$dont_check_email_mobile) {
                    $email_mobile = $objQuery->get('email_mobile', 'dtb_customer', 'customer_id = ?', array($_SESSION['customer']['customer_id']));

                    return isset($email_mobile);
                }

                return true;
            }
        }

        return false;
    }
}
