<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            Evolution Chart
        </x-fonts.sub-header>
        <a href="{{ route('digigarden') }}">
            <x-buttons.button type="edit" icon="fa-arrow-left" label="Back" />
        </a>
    </x-slot>
    <x-container class="p-2 md:p-4">
        <x-slot name="header">
            <x-fonts.sub-header class="text-accent">Evolution Chart</x-fonts.sub-header>
        </x-slot>
        <x-slot name="info">
            <x-fonts.paragraph>
                This site lets you collect, evolve, and manage digital creatures. Start by registering an account to receive dataCrystals, which you can use to choose an egg from the DigiConverge page. Hatch and evolve your creatures in the DigiGarden, track their progress, and build your ultimate digital team!
            </x-fonts.paragraph>
        </x-slot>

        <div class="container text-center">
            <h2>Monster Evolution Chart</h2>

            <div class="d-flex justify-content-center">
                <ul class="tree">
                    @foreach($evolutionTrees as $tree)
                    @php
                    function renderNode($node, $userMonsterIds) {
                    $html = '<li>';

                        if (in_array($node['monster']->id, $userMonsterIds)) {
                        $html .= '<div>' . e($node['monster']->name) . '</div>';
                        } else {
                        $html .= '<div><i class="fas fa-question-circle fa-2x text-muted"></i></div>';
                        }

                        if (!empty($node['children'])) {
                        $html .= '<ul class="tree">';
                            foreach ($node['children'] as $child) {
                            $html .= renderNode($child, $userMonsterIds);
                            }
                            $html .= '</ul>';
                        }

                        $html .= '</li>';
                    return $html;
                    }
                    @endphp

                    {!! renderNode($tree, $userMonsterIds) !!}
                    @endforeach
                </ul>
            </div>
        </div>
    </x-container>
</x-app-layout>