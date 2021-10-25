<?php
function addCard($data)
{
    list('image' => $image, 'cardBgColor' => $cardBgColor, 'title' => $title, 'text' => $text, 'small_text' => $small_text) = $data;
    $card = '<div class="card">
                <img class="card-img-top img-responsive" src="' . $image . '" alt="Card image cap" />
                <div class="card-body ' . $cardBgColor . ' ' . (empty($cardBgColor) ? '' : 'text-white') . '">
                    <h5 class="card-title">' . $title . '</h5>
                    <p class="card-text">' . $text . '</p>
                    <p class="card-text"><small class="text-muted">' . $small_text . '</small></p>
                </div>
               </div>';
    echo $card;
}

function addPaginationNav($data)
{
    list(
        'total_pages' => $totalPages,
        'hasPrevPage' => $hasPrevPage,
        'hasNextPage' => $hasNextPage,
        'url' => $url,
        'totalPages' => $totalPages,
        'currentPage' => $currentPage,
        'prevPage' => $prevPage,
        'nextPage' => $nextPage
    ) = $data;
    if (isset($totalPages)) {
        $top = '<div class="row mb-5 mt-4">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <nav aria-label="Page navigation example">
                            <ul class="pagination flex-wrap">
                            <li class="page-item' . ($hasPrevPage ? '' : ' disabled') . '"><a class="page-link" href="' . $url . "/" . $prevPage . '">Previous</a></li>';

        $body = '';
        for ($page = 1; $page <= $totalPages; $page++) {
            $body .= '<li class="page-item' . ($page == $currentPage ? ' active' : '') . '"><a class="page-link" href="' . $url . "/" . $page . '">' . $page . '</a></li>';
        }

        $top .= $body;

        $top .= '<li class="page-item' . ($hasNextPage ? '' : ' disabled') . '"><a class="page-link" href="' . $url . "/" . $nextPage . '">Next</a></li>
                     </ul></nav></div>';

        echo $top;
    }
}
