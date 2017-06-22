<?php

namespace App\ViewComposers\Frontend;

/**
 * Class LayoutPartComposer
 * @package App\ViewComposers\Frontend
 */
class LayoutPartComposer
{
    /**
     * @return string
     */
    protected function layoutPartHtml(string $layoutPart)
    {
        $layoutPartHtml = '';

        $modules = $this->getLayoutPartModules($layoutPart);

        foreach ($modules as $module) {
            $layoutPartHtml = $layoutPartHtml . \View::make('frontend.modules.' . $module)->render();
        }

        return $layoutPartHtml;
    }

    /**
     * Returns an array with the layout part's module names. First module has
     * highest priority and should be displayed on top.
     *
     * @return array
     */
    private function getLayoutPartModules($layoutPart): array
    {
        /** @var array $modules */
        $modules = \DB::table('modules')->select(['modules.name as name'])
            ->join('layout_part_module', 'layout_part_module.module_id', 'modules.id')
            ->join('layout_parts', 'layout_parts.id', 'layout_part_module.layout_part_id')
            ->where('layout_parts.name', $layoutPart)
            ->orderBy('layout_part_module.priority', 'asc')
            ->get()
            ->pluck('name')
            ->toArray();

        return $modules;
    }
}
