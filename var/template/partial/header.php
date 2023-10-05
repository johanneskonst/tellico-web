<!DOCTYPE html>
<html lang="en">
    <head>
        <title> TellicoWeb </title>
        <link rel="stylesheet" href="/assets/picnic.min.css">
        <style type="text/css">
            aside a.top {
                /*font-size: 0;*/
                position: fixed;
                bottom: 0;
                font-weight: bold;
                width: 180px;
                padding: .6em 0;
                margin-bottom: 0;
                border-radius: .3em .3em 0 0;
                transition: all .3s ease;
            }

            aside a.top.visible {
                font-size: 1em;
            }

            aside .links a.button {
                text-align: left;
            }

            @media all and (max-width: 1000px) {
                aside a.pseudo.top {
                    background: rgba(255, 255, 255, .8);
                    width: 100%;
                    left: 0;
                    text-align: center;
                    z-index: 100;
                }
            }
            nav.mainnav {
                padding: 2em 0;
            }
            .mainnav .logo {
                height: 3em;
            }
            .small-footnote {
                color: #777;
                font-size: 0.7em;
                text-align: right;
            }
        </style>
    </head>
    <body>
        <nav class="mainnav">
            <a href="<?php echo $_ENV['BASE_PATH'] . '/'; ?>" class="brand"><img class="logo" src="/assets/logo.png" /><span>TellicoWeb</span></a>
            <div class="menu">
                <select id="tw-df-s" onchange="top.location = (document.getElementById('tw-df-s').value)">
                    <option value="<?php echo $_ENV['BASE_PATH'] . '/'; ?>"> &nbsp;&nbsp;&nbsp;&nbsp; -- select a datafile -- &nbsp;&nbsp;&nbsp;&nbsp; </option>
<?php if (count($this->datafiles)) { foreach ($this->datafiles as $file) { ?>
                    <option value="<?php echo $_ENV['BASE_PATH'] . '/' . $file->getName('.tc'); if ($this->datafile && $file->getName() == $this->datafile->getName()) echo '" selected="selected'; ?>"><?php echo $file->getName(); ?> (<?php echo $file->getFormattedSize(); ?>) &nbsp;&nbsp;&nbsp;&nbsp;</option>
<?php } } else { ?>
                    <option value="<?php echo $_ENV['BASE_PATH'] . '/'; ?>">no databases found</option>
<?php } ?>
                </select>
            </div>
        </nav>
        <main style="margin-top: 50px; padding-left: 20px; padding-right: 20px;">
            <section class="flex five">
                <aside class="full fifth-1000">
                    <h2>Databases</h2>
                    <div class="links flex two three-500 five-800 one-1000">
<?php if (count($this->datafiles)) { foreach ($this->datafiles as $file) { ?>
                            <li><a href="<?php echo $_ENV['BASE_PATH'] . '/' . $file->getName('.tc'); ?>"><?php echo $file->getName(); ?></a><br /><small><?php echo $file->getFormattedSize(), ' - ', $file->getFormattedModified(), '<br>', $file->getCollection()->getEntryCount(), ' entries, ', $file->getCollection()->getFieldCount(), ' fields, ', $file->getCollection()->getImageCount(), ' images.', ($file->isContained() ? '(<span title="Contained Archive">CA</span>)' : '(<span title="Externally Stored Images">ESI</span>)'), '</small>'; ?></li>
<?php } } else { ?>
                            <li>no databases found</li>
<?php } ?>
                        </ul>
                    </div>
                    <a href="#home" tabindex="-1" class="top pseudo button">▲ Up ▲</a>
                </aside>
                <article class="full four-fifth-1000">
