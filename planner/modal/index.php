<?php
$method = isset($_POST['method']) ? $_POST['method'] : (isset($_GET['method']) ? $_GET['method'] : '');

if ($method === 'pdf') {

$default_settings = array(
    'orientation'        => 1,
    'address'            => '',
    'number'             => '',
    'allPlans'           => true,
    'currentPlan'        => false,
    'checkedPages'       => array(0),
    'projections'        => false,
    'splitCables'        => false,
    'fontScale'          => 'auto',
    'fontSize'           => '8',
    'scaleByTerritories' => '0',
    'commonScale'        => false,
    'hideLegends'        => false,
    'scaleByComments'    => true,
    'scaleByRulers'      => true,
    'scaleByOuterItems'  => false,
    'scaleByFigures'     => true,
    'scaleByPictures'    => true,
    'cover'              => '',
    'coverTitleFontSize' => '72',
    'titles'             => array(),
    'coverTitle'         => '',
    'year'               => date('Y'),
);

$print_settings_b64 = base64_encode(json_encode($default_settings));

?>
<div id="fog_window_wrapper">
<div id="fog_window">
    <div id="fog_window_close" onclick="return close_v2_modal();">закрыть окно</div>
    <div id="fog_window_content">

        <div id="pdf_settings_core" data-print_settings="<?php echo $print_settings_b64; ?>">
            <div class="pdf_settings_core_input width-30">
                <label>Ориентация:</label>
                <select name="pdf_orientation">
                    <option value="landscape" selected="">Альбомная</option>
                    <option value="portrait">Портретная</option>
                </select>
            </div>
            <div class="pdf_settings_core_input width-50">
                <label>Название, адрес объекта:</label>
                <input name="pdf_address" value="" maxlength="60" type="text">
            </div>
            <div class="pdf_settings_core_input width-20">
                <label>Проект №:</label>
                <input name="pdf_number" value="" maxlength="12" type="text" placeholder="">
            </div>
        </div>

        <div id="pdf_settings_zone" data-print_settings="">
            <div class="pdf_settings_zone_item active" id="pdf_settings_zone_default">
                <a>Задать фрагмент чертежей для альбома</a>
            </div>
            <div class="pdf_settings_zone_item" id="pdf_settings_zone_ready">
                <span>Проект будет отмасштабирован по фрагменту</span>
                <a class="delete">Удалить фрагмент</a>
            </div>
        </div>

        <div id="pdf_settings_tabs">
            <div data-type="content" class="pdf_settings_tab active">Набор листов</div>
            <div data-type="decoration" class="pdf_settings_tab">Оформление</div>
        </div>

        <div id="pdf_settings_view" class="hidden tabbed tab_decoration">
            <div id="pdf_settings_font_scale" class="pdf_settings_core_input width-50">
                <label>Масштабирование размеров:</label>
                <select name="pdf_fonts_scale">
                    <option value="auto">Автоматическое</option>
                    <option value="manual">Задать вручную</option>
                    <option value="none">Отключено</option>
                </select>
            </div>
            <div id="pdf_settings_font_size" class="pdf_settings_core_input width-15">
                <select name="pdf_cover_title_font_size">
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="15">15</option>
                    <option value="18">18</option>
                    <option value="21">21</option>
                    <option value="24">24</option>
                </select>
            </div>
            <div class="pdf_settings_core_input width-50">
                <label>Граница участка:</label>
                <select name="scale_by_territories">
                    <option value="0">Не включать в альбом</option>
                    <option value="1">Только на исходном плане</option>
                    <option value="2">Включить на всех листах</option>
                </select>
            </div>
        </div>

        <div id="pdf_settings_plans">
            <div class="pdf_settings_plan tabbed tab_content" id="config_pdf_plans">
                <input type="checkbox" checked="checked" name="plan[0]" id="pad_plan0_box">
                <label for="pad_plan0_box">Включить все нарисованные листы</label>
            </div>

            <div class="pdf_settings_plan tabbed tab_content" id="config_projections">
                <input type="checkbox" name="plan[-1]" id="pad_plan-2_box">
                <label for="pad_plan-2_box">Включить развертки стен</label>
            </div>

            <div class="pdf_settings_plan tabbed tab_content" id="config_pdf_plans-split_cables">
                <input type="checkbox" name="plan[-1]" id="pad_plan-3_box">
                <label for="pad_plan-3_box">Разделить электропроводку по группам</label>
            </div>

            <div class="pdf_settings_plan tabbed tab_decoration" id="config_pdf_plans_common_scale" style="display:none;">
                <input type="checkbox" name="plan[-1]" id="pad_plan-5_box">
                <label for="pad_plan-5_box">Единый масштаб на всех листах</label>
            </div>
            <div class="pdf_settings_plan tabbed tab_decoration" id="config_pdf_plans_no_legend" style="display:none;">
                <input type="checkbox" name="plan[-1]" id="pad_plan-6_box">
                <label for="pad_plan-6_box">Скрыть условные обозначения</label>
            </div>

            <div id="config_pdf_columns" class="hidden tabbed tab_content">
                <div class="pdf_settings_plan hidden" data-plan="init"><input type="checkbox" data-n="1" name="plan[1]" id="pad_plan1_box"><label for="pad_plan1_box">Исходный план</label></div>
                <div class="pdf_settings_plan hidden" data-plan="break_walls"><input type="checkbox" data-n="2" name="plan[2]" id="pad_plan2_box"><label for="pad_plan2_box">Демонтаж</label></div>
                <div class="pdf_settings_plan hidden" data-plan="create_walls"><input type="checkbox" data-n="3" name="plan[3]" id="pad_plan3_box"><label for="pad_plan3_box">Перегородки</label></div>
                <div class="pdf_settings_plan hidden" data-plan="rooms"><input type="checkbox" data-n="4" name="plan[4]" id="pad_plan4_box"><label for="pad_plan4_box">Помещения</label></div>
                <div class="pdf_settings_plan hidden" data-plan="radiators"><input type="checkbox" data-n="5" name="plan[5]" id="pad_plan5_box"><label for="pad_plan5_box">Радиаторы</label></div>
                <div class="pdf_settings_plan hidden" data-plan="furniture"><input type="checkbox" data-n="6" name="plan[6]" id="pad_plan6_box"><label for="pad_plan6_box">Мебель</label></div>
                <div class="pdf_settings_plan hidden" data-plan="santeh"><input type="checkbox" data-n="7" name="plan[7]" id="pad_plan7_box"><label for="pad_plan7_box">Сантехника</label></div>
                <div class="pdf_settings_plan hidden" data-plan="waterplan"><input type="checkbox" data-n="8" name="plan[8]" id="pad_plan8_box"><label for="pad_plan8_box">Водоснабжение</label></div>
                <div class="pdf_settings_plan hidden" data-plan="sockets"><input type="checkbox" data-n="9" name="plan[9]" id="pad_plan9_box"><label for="pad_plan9_box">Розетки</label></div>
                <div class="pdf_settings_plan hidden" data-plan="light_connections"><input type="checkbox" data-n="11" name="plan[11]" id="pad_plan11_box"><label for="pad_plan11_box">Выключатели</label></div>
                <div class="pdf_settings_plan hidden" data-plan="light"><input type="checkbox" data-n="10" name="plan[10]" id="pad_plan10_box"><label for="pad_plan10_box">Освещение</label></div>
                <div class="pdf_settings_plan hidden" data-plan="cables"><input type="checkbox" data-n="14" name="plan[14]" id="pad_plan14_box"><label for="pad_plan14_box">Электропроводка</label></div>
                <div class="pdf_settings_plan hidden" data-plan="shield"><input type="checkbox" data-n="24" name="plan[24]" id="pad_plan24_box"><label for="pad_plan24_box">Электрощиток</label></div>
                <div class="pdf_settings_plan hidden" data-plan="warm_floor"><input type="checkbox" data-n="12" name="plan[12]" id="pad_plan12_box"><label for="pad_plan12_box">Теплые полы</label></div>
                <div class="pdf_settings_plan hidden" data-plan="conditioners"><input type="checkbox" data-n="13" name="plan[13]" id="pad_plan13_box"><label for="pad_plan13_box">Кондиционеры</label></div>
                <div class="pdf_settings_plan hidden" data-plan="ventilation"><input type="checkbox" data-n="22" name="plan[22]" id="pad_plan22_box"><label for="pad_plan22_box">Вентиляция</label></div>
                <div class="pdf_settings_plan hidden" data-plan="security"><input type="checkbox" data-n="23" name="plan[23]" id="pad_plan23_box"><label for="pad_plan23_box">Безопасность</label></div>
                <div class="pdf_settings_plan hidden" data-plan="floor"><input type="checkbox" data-n="15" name="plan[15]" id="pad_plan15_box"><label for="pad_plan15_box">Напольные покрытия</label></div>
                <div class="pdf_settings_plan hidden" data-plan="walls"><input type="checkbox" data-n="16" name="plan[16]" id="pad_plan16_box"><label for="pad_plan16_box">Отделка стен</label></div>
                <div class="pdf_settings_plan hidden" data-plan="ceiling"><input type="checkbox" data-n="17" name="plan[17]" id="pad_plan17_box"><label for="pad_plan17_box">Потолки</label></div>
                <div class="pdf_settings_plan hidden" data-plan="hydroisolation"><input type="checkbox" data-n="18" name="plan[18]" id="pad_plan18_box"><label for="pad_plan18_box">Гидроизоляция</label></div>
                <div class="pdf_settings_plan hidden" data-plan="isolation"><input type="checkbox" data-n="19" name="plan[19]" id="pad_plan19_box"><label for="pad_plan19_box">Изоляция</label></div>
                <div class="pdf_settings_plan hidden" data-plan="shtukaturka"><input type="checkbox" data-n="20" name="plan[20]" id="pad_plan20_box"><label for="pad_plan20_box">Штукатурка</label></div>
                <div class="pdf_settings_plan hidden" data-plan="styazhka"><input type="checkbox" data-n="21" name="plan[21]" id="pad_plan21_box"><label for="pad_plan21_box">Стяжка</label></div>
                <div class="pdf_settings_plan hidden" style="display:none;" data-plan="projections"><input type="checkbox" data-n="0" name="plan[0]" id="pad_plan0_proj_box"><label for="pad_plan0_proj_box">Развертки стен</label></div>
            </div>
        </div>

        <div id="pdf_settings_scale_elements" class="hidden tabbed tab_decoration" style="display:none;">
            <div id="pdf_settings_scale_elements_label">Учитывать при масштабировании:</div>
            <div class="pdf_settings_box" id="scale_by_comments"><input type="checkbox" checked="checked" name="scale_elements[comments]" id="se_comments"><label for="se_comments">Текстовые комментарии</label></div>
            <div class="pdf_settings_box" id="scale_by_rulers"><input type="checkbox" checked="checked" name="scale_elements[sizes]" id="se_sizes"><label for="se_sizes">Добавленные размеры</label></div>
            <div class="pdf_settings_box" id="scale_by_outer_items"><input type="checkbox" name="scale_elements[items]" id="se_items"><label for="se_items">Предметы вне помещений</label></div>
            <div class="pdf_settings_box" id="scale_by_figures"><input type="checkbox" checked="checked" name="scale_elements[figures]" id="se_figures"><label for="se_figures">Фигуры и линии</label></div>
            <div class="pdf_settings_box" id="scale_by_pictures"><input type="checkbox" checked="checked" name="scale_elements[images]" id="se_images"><label for="se_images">Изображения</label></div>
        </div>

        <div id="pdf_settings_cover" class="tabbed tab_decoration" style="display:none;">
            <div id="pdf_settings_cover_label">Обложка альбома:</div>
            <div id="pdf_settings_cover_items">
                <div class="pdf_settings_cover_item active">
                    <a><span>Без<br> обложки</span></a>
                </div>
                <div class="pdf_settings_cover_item" data-file="pdf_cover_1.svg" data-name="cover_1">
                    <a><img src="/images/planner/pdf_covers/thumbs/1.jpg" alt=""></a>
                </div>
                <div class="pdf_settings_cover_item" data-file="pdf_cover_2.svg" data-name="cover_2">
                    <a><img src="/images/planner/pdf_covers/thumbs/2.jpg" alt=""></a>
                </div>
                <div class="pdf_settings_cover_item" data-file="pdf_cover_3.svg" data-name="cover_3">
                    <a><img src="/images/planner/pdf_covers/thumbs/3.jpg" alt=""></a>
                </div>
                <div class="pdf_settings_cover_item" data-file="pdf_cover_4.svg" data-name="cover_4">
                    <a><img src="/images/planner/pdf_covers/thumbs/4.jpg" alt=""></a>
                </div>
            </div>
        </div>

        <div id="pdf_settings_cover_options" class="tabbed tab_decoration">
            <div class="pdf_settings_core_input width-50">
                <label>Заголовок обложки:</label>
                <input name="pdf_cover_title" value="" placeholder="Альбом чертежей" maxlength="30" type="text">
            </div>
            <div class="pdf_settings_core_input width-30">
                <label>Шрифт заголовка:</label>
                <select name="pdf_cover_title_font">
                    <option value="72">72</option>
                    <option value="68">68</option>
                    <option value="64">64</option>
                    <option value="60">60</option>
                    <option value="56">56</option>
                </select>
            </div>
            <div class="pdf_settings_core_input width-20">
                <label>Год:</label>
                <input name="pdf_year" value="" maxlength="10" type="text" placeholder="<?php echo date('Y'); ?>">
            </div>
        </div>

        <div id="pdf_settings_start">
            <div class="pdf_settings_core_input width-50">
                <label>Подпись, автор проекта:</label>
                <input name="pdf_author" value="" type="text">
            </div>
            <div class="pdf_settings_core_input width-50">
                <a class="mce-button green" id="print-ok">Сгенерировать файл .pdf</a>
            </div>
        </div>

    </div>
</div>
</div>
<?php
    exit;
}

if ($method === 'hotkeys') {
?>
<div id="fog_window_wrapper">
<div id="fog_window">
    <div id="fog_window_close" onclick="return close_v2_modal();">закрыть окно</div>
    <div id="fog_window_content">
        <div id="hotkeys_modal_content"></div>
    </div>
</div>
</div>
<?php
    exit;
}

echo '<div id="fog_window"><div id="fog_window_content">Неизвестный метод: ' . htmlspecialchars($method) . '</div></div>';
