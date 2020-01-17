<?php

declare(strict_types=1);

namespace Archette\FormUtils\Renderer;

use DateInput;
use Nette\Forms\Controls\BaseControl;
use Nette\Forms\Controls\Checkbox;
use Nette\Forms\Controls\CheckboxList;
use Nette\Forms\Controls\RadioList;
use Nette\Forms\IControl;
use Nette\Forms\Rendering\DefaultFormRenderer;
use Nette\Forms\Controls;
use Nette\Utils\Html;

class BootstrapRenderer extends DefaultFormRenderer
{
	/** @var bool */
	private $controlsInit = false;

	public function __construct()
	{
		$this->wrappers['controls']['container'] = null;
		$this->wrappers['pair']['container'] = 'div class="form-group row"';
		$this->wrappers['pair']['.error'] = 'has-error';
		$this->wrappers['control']['container'] = 'div class=col-sm-12';
		$this->wrappers['label']['container'] = 'div class="col-sm-12 control-label"';
		$this->wrappers['control']['description'] = 'div class=note';
		$this->wrappers['control']['errorcontainer'] = 'div class=note';
		$this->wrappers['error']['container'] = 'section';
		$this->wrappers['error']['item'] = 'p class="alert alert-danger alert-form"';
	}

	public function renderBegin(): string
	{
		$this->controlsInit();
		return parent::renderBegin();
	}

	public function renderEnd(): string
	{
		$this->controlsInit();
		return parent::renderEnd();
	}

	public function renderBody(): string
	{
		$this->controlsInit();
		return parent::renderBody();
	}

	public function renderControls($parent): string
	{
		$this->controlsInit();
		return parent::renderControls($parent);
	}

	public function renderPair(IControl $control): string
	{
		$this->controlsInit();

		if ($control instanceof RadioList || $control instanceof CheckboxList) {
			$radios = Html::el(null);
			$pair = $this->getWrapper('pair container');

			$pair->addHtml(Html::el('label', ['class' => 'col-12'])->addText($control->getLabel()->getText()));
			$pair->addHtml($this->generateControls($control));

			$radios->addHtml($pair);

			return $radios->render(0);

		} elseif ($control instanceof Checkbox) {
			$radios = Html::el(null);
			$pair = $this->getWrapper('pair container');

			$pair->addHtml($this->generateControls($control));

			$radios->addHtml($pair);

			return $radios->render(0);
		}

		return parent::renderPair($control);
	}

	protected function generateControls(BaseControl $control): Html
	{
		$wrapper = Html::el('div', ['class' => 'col-12']);

		if ($control instanceof RadioList || $control instanceof CheckboxList) {
			foreach ($control->getItems() as $key => $labelTitle) {
				if ($control instanceof RadioList) {
					$container = Html::el('div', ['class' => 'custom-control custom-radio custom-control-inline mb-5']);
				} else {
					$container = Html::el('div', ['class' => 'custom-control custom-checkbox custom-control-inline mb-5']);
				}

				$input = $control->getControlPart($key);
				$label = $control->getLabelPart($key);

				$label->setAttribute('class', 'custom-control-label');
				$input->setAttribute('class', 'custom-control-input');

				$container->addHtml($input);
				$container->addHtml($label);

				$wrapper->addHtml($container);
			}
		} else if ($control instanceof Checkbox) {
			$container = Html::el('div', ['class' => 'custom-control custom-checkbox custom-control-inline']);

			$input = $control->getControlPart();
			$input->setAttribute('class', 'custom-control-input');

			$label = Html::el('label', ['class' => 'custom-control-label', 'for' => $control->getHtmlId()]);
			$label->addText($control->getCaption());

			$container->addHtml($input);
			$container->addHtml($label);

			$wrapper->addHtml($container);
		}

		return $wrapper;
	}

	public function renderPairMulti(array $controls): string
	{
		$this->controlsInit();
		return parent::renderPairMulti($controls);
	}

	public function renderLabel(IControl $control): Html
	{
		$this->controlsInit();
		return parent::renderLabel($control);
	}

	public function renderControl(IControl $control): Html
	{
		$this->controlsInit();
		return parent::renderControl($control);
	}

	private function controlsInit(): void
	{
		if ($this->controlsInit) {
			return;
		}

		$this->controlsInit = true;
		$this->form->getElementPrototype()->appendAttribute('class', 'form-horizontal');

		foreach ($this->form->getControls() as $control) {
			$type = $control->getOption('type');
			if (in_array($type, ['text', 'textarea', 'select'], true)) {
				$control->getControlPrototype()->addClass('form-control');

			} elseif (in_array($type, ['checkbox', 'radio'], true)) {
				$control->getSeparatorPrototype()->setName('div')->addClass($type);

			} elseif ($control instanceof Controls\Button) {
				$class = empty($usedPrimary) ? 'btn btn-primary btn-block' : 'btn btn-default btn-block';
				$control->getControlPrototype()->appendAttribute('class', $class);
				$usedPrimary = true;

			} elseif ($control instanceof Controls\TextBase) {
				$control->getControlPrototype()->appendAttribute('class', 'form-control');

			} elseif ($control instanceof DateInput) {
				$control->getControlPrototype()->appendAttribute('class', 'form-control form-control-date');

			} elseif ($control instanceof Controls\Checkbox) {
				$control->getControlPrototype()->appendAttribute('class', 'switch');
				$control->getControlPart()->appendAttribute('class', 'default');
			}

			if ($control instanceof Controls\SelectBox || $control instanceof Controls\MultiSelectBox) {
				$control->getControlPrototype()->appendAttribute('class', 'select2');
			}
		}

		foreach ($this->form->getControls() as $control) {
			$type = $control->getOption('type');

			if (in_array($type, ['text', 'textarea', 'select'], true)) {
				$control->getControlPrototype()->appendAttribute('class', 'form-control');
			}
		}
	}
}
