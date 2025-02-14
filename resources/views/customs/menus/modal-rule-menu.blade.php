<div class="modal fade" id="ruleMenu" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-simple modal-dialog-centered modal-edit-role">
        <div class="modal-content" style="z-index: 9999;">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                    <h4 class="role-title mb-2">{!! __('menu_rule_title_guideline') !!}</h4>
                </div>
                <div class="px-4">
                    <div class="accordion accordion-flush" id="accordionExample">
                        <!-- Part 1: Menu Components -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                    1. {!! __('menu_components_guideline') !!}
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <ul>
                                        <li><strong>{!! __('menu_name_guideline') !!}</strong>: {!! __('menu_name_description_guideline') !!}</li>
                                        <li><strong>{!! __('menu_parent_guideline') !!}</strong>: {!! __('menu_parent_description_guideline') !!}</li>
                                        <li><strong>{!! __('menu_icons_guideline') !!}</strong>: {!! __('menu_icons_description_guideline') !!}</li>
                                        <li><strong>{!! __('menu_slug_guideline') !!}</strong>: {!! __('menu_slug_description_guideline') !!}</li>
                                        <li><strong>{!! __('menu_url_guideline') !!}</strong>: {!! __('menu_url_description_guideline') !!}</li>
                                        <li><strong>{!! __('menu_is_group_guideline') !!}</strong>: {!! __('menu_is_group_description_guideline') !!}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Part 2: Slug Rules -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    2. {!! __('menu_slug_rules_guideline') !!}
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <ul>
                                        <li><strong>{!! __('menu_parent_guideline') !!}</strong>: {!! __('menu_slug_parent_example_guideline') !!}</li>
                                        <li><strong>{!! __('menu_child_guideline') !!}</strong>: {!! __('menu_slug_child_example_guideline') !!}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Part 3: URL Rules -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    3. {!! __('menu_url_rules_guideline') !!}
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <ul>
                                        <li><strong>{!! __('menu_parent_guideline') !!}</strong>: {!! __('menu_url_parent_example_guideline') !!}</li>
                                        <li><strong>{!! __('menu_child_guideline') !!}</strong>: {!! __('menu_url_child_example_guideline') !!}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Part 4: Icon Rules -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingFour">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                    4. {!! __('menu_icon_rules_guideline') !!}
                                </button>
                            </h2>
                            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <p>{!! __('menu_icon_description_guideline') !!}</p>
                                    <p>{!! __('menu_icon_example_guideline') !!}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Part 5: Is Group Menu -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingFive">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                    5. {!! __('menu_is_group_guideline') !!}
                                </button>
                            </h2>
                            <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <p>{!! __('menu_is_group_info_guideline') !!}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Part 6: Active Menu Rules -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingSix">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                    6. {!! __('menu_active_rules_guideline') !!}
                                </button>
                            </h2>
                            <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <ul>
                                        <li><strong>{!! __('menu_parent_guideline') !!}</strong>: {!! __('menu_active_parent_info_guideline') !!}</li>
                                        <li><strong>{!! __('menu_child_guideline') !!}</strong>: {!! __('menu_active_child_info_guideline') !!}</li>
                                        <li><strong>{!! __('menu_group_guideline') !!}</strong>: {!! __('menu_group_info_guideline') !!}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Part 7: Menu Creation Process -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingSeven">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                                    7. {!! __('menu_creation_process_guideline') !!}
                                </button>
                            </h2>
                            <div id="collapseSeven" class="accordion-collapse collapse" aria-labelledby="headingSeven" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <p>{!! __('menu_creation_steps_guideline') !!}</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
