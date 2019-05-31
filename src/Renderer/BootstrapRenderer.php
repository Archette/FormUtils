<?php

declare(strict_types=1);

namespace Archette\FormUtils\Renderer;

use DateInput;
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
		return parent::renderPair($control);
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
		$this->form->getElementPrototype()->addClass('form-horizontal');

		foreach ($this->form->getControls() as $control) {
			$type = $control->getOption('type');
			if (in_array($type, ['text', 'textarea', 'select'], true)) {
				$control->getControlPrototype()->addClass('form-control');

			} elseif (in_array($type, ['checkbox', 'radio'], true)) {
				$control->getSeparatorPrototype()->setName('div')->addClass($type);

			} elseif ($control instanceof Controls\Button) {
				$class = empty($usedPrimary) ? 'btn btn-primary btn-block' : 'btn btn-default btn-block';
				$control->getControlPrototype()->addClass($class);
				$usedPrimary = true;

			} elseif ($control instanceof Controls\TextBase) {
				$control->getControlPrototype()->addClass('form-control');

			} elseif ($control instanceof DateInput) {
				$control->getControlPrototype()->addClass('form-control form-control-date');

			} elseif ($control instanceof Controls\SelectBox) {
				$control->getControlPrototype()->addClass('select2');

			} elseif ($control instanceof Controls\MultiSelectBox) {
				$control->getControlPrototype()->addClass('select2');

			} elseif ($control instanceof Controls\Checkbox) {
				$control->getControlPrototype()->addClass('switch');
				$control->getControlPart()->addClass('default');
			}
		}

		foreach ($this->form->getControls() as $control) {
			$type = $control->getOption('type');

			if (in_array($type, ['text', 'textarea', 'select'], true)) {
				$control->getControlPrototype()->addClass('form-control');

			} elseif (in_array($type, ['checkbox', 'radio'], true)) {
				$control->getSeparatorPrototype()->setName('div')->addClass($type);
			}
		}
	}
}
