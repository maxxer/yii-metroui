<?php

Yii::import('zii.widgets.CMenu');

/**
 * ## YMetroUiMenu class file.
 *
 * @author Lorenzo Milesi <hotspot@internavigare.com>
 * @copyright Copyright &copy; Internavigare S.r.l. 2014
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */
class YMetroUiNavbar extends CMenu
{

    // Navbar types.
    const TYPE_DARK = 'dark';
    const TYPE_LIGHT = 'light';
    const TYPE_WHITE = 'white';
    // Navbar fix locations.
    const FIXED_TOP = 'top';
    const FIXED_BOTTOM = 'bottom';

    /**
     * @var string the navbar type. Valid values are TYPE_*.
     * @since 1.0.0
     */
    public $type = self::TYPE_DARK;

    /**
     * @var string the text for the brand.
     */
    public $brand;

    /**
     * @var string the URL for the brand link.
     */
    public $brandUrl;

    /**
     * @var array the HTML attributes for the brand link.
     */
    public $brandOptions = array();

    /**
     * @var array navigation items.
     * @since 0.9.8
     */
    public $items = array();

    /**
     * @var mixed fix location of the navbar if applicable.
     * Valid values are 'top' and 'bottom'. Defaults to 'top'.
     * Setting the value to false will make the navbar static.
     * @since 0.9.8
     */
    public $fixed = false;

    /**
     * @var array the HTML attributes for the widget container.
     */
    public $htmlOptions = array();

    /**
     * ### .init()
     *
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();
        if ($this->brand !== false) {
            if (!isset($this->brand)) {
                $this->brand = CHtml::encode(Yii::app()->name);
            }

            if (!isset($this->brandUrl)) {
                $this->brandUrl = Yii::app()->homeUrl;
            }

            if (isset($this->brandOptions['class'])) {
                $this->brandOptions['class'] .= ' element';
            } else {
                $this->brandOptions['class'] = 'element';
            }
        }

        $classes = array('navigation-bar');

        if (isset($this->type) && in_array($this->type, array(self::TYPE_DARK, self::TYPE_LIGHT, self::TYPE_WHITE))) {
            $classes[] = $this->type;
        }

        // add the dropdown-menu options
        $this->submenuHtmlOptions = array('class' => 'dropdown-menu ' . $this->type, 'data-role' => 'dropdown');

        if ($this->fixed !== false && in_array($this->fixed, array(self::FIXED_TOP, self::FIXED_BOTTOM))) {
            $classes[] = 'fixed-' . $this->fixed;
        }

        if (!empty($classes)) {
            $classes = implode(' ', $classes);
            if (isset($this->htmlOptions['class'])) {
                $this->htmlOptions['class'] .= ' ' . $classes;
            } else {
                $this->htmlOptions['class'] = $classes;
            }
        }
    }

    /**
     * ### .run()
     *
     * Runs the widget.
     */
    public function run()
    {
        echo CHtml::openTag('div', $this->htmlOptions);
        echo CHtml::openTag('div', array('class' => 'navigation-bar-content container'));

        if ($this->brand !== false) {

            if ($this->brandUrl !== false) {
                echo CHtml::openTag("a", $this->brandOptions);
                echo CHtml::tag("span", array('class' => 'icon-grid-view'), $this->brand);
                echo CHtml::closeTag("a");
            } else {
                echo CHtml::openTag('span', $this->brandOptions) . $this->brand . '</span>';
            }
            echo CHtml::tag("span", array('class' => 'element-divider'), "&nbsp;");
        }

        $this->renderMenu($this->items);

        CHtml::closeTag("div"); // navigation-bar-content container
        CHtml::closeTag("div"); // main
    }

    /**
     * @inherit
     */
    protected function renderMenu($items)
    {
        if (count($items)) {
            echo '<a class="element1 pull-menu" href="#"></a>';
            echo CHtml::openTag('ul', array('class' => 'element-menu')) . "\n";
            $this->renderMenuRecursive($items);
            echo CHtml::closeTag('ul');
        }
    }

    /**
     * @inherit
     */
    protected function renderMenuItem($item)
    {
        // Special case: sub items
        if(isset($item['items']) && count($item['items'])) {
            $item['url'] = "#";
            if (isset($item['linkOptions']) && isset($item['linkOptions']['class'])) 
                $item['linkOptions']['class'] .= " dropdown-toggle";
            else 
                $item['linkOptions']['class'] = " dropdown-toggle";
        }
        if (isset($item['url'])) {
            $label = $this->linkLabelWrapper === null ? $item['label'] : CHtml::tag($this->linkLabelWrapper, $this->linkLabelWrapperHtmlOptions, $item['label']);
            return CHtml::link($label, $item['url'], isset($item['linkOptions']) ? $item['linkOptions'] : array());
        } else
            return CHtml::tag('span', isset($item['linkOptions']) ? $item['linkOptions'] : array(), $item['label']);
    }

}
