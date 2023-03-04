<?php
$tmpImport = isset($web['importPlugins']) ? $web['importPlugins'] : array();
if (count($tmpImport) > 0) {
    foreach ($tmpImport as $tmpPlugins) {
        if (!is_array($tmpPlugins['js']))
            echo ($tmpPlugins['js'] != "") ? "<script src='" . $tmpPlugins['js'] . "'></script>\n" : "";
        elseif (count($tmpPlugins['js']) > 1) {
            foreach ($tmpPlugins['js'] as $tmpPluginsExt)
                echo ($tmpPluginsExt != "") ? "<script src='" . $tmpPluginsExt . "'></script>\n" : "";
        }
    }
}
$importJs = isset($web['importJs']) ? $web['importJs'] : array();
if (count($importJs) > 0) {
    foreach ($importJs as $import)
        if (filter_var($import, FILTER_VALIDATE_URL)) {
            echo "<script src='" . $import . "'></script>\n";
        } else {
            $this->load->view($import);
        }
}
