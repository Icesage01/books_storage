<?php

namespace src\Presentation\View\widgets;

use yii\bootstrap5\Html;
use yii\base\Widget;

class PaginationWidget extends Widget
{
    public array $paginationInfo;
    public string $route = '';
    public array $params = [];
    
    public function run(): string
    {
        if ($this->paginationInfo['totalPages'] <= 1) {
            return '';
        }
        
        $html = '<nav aria-label="Навигация по страницам">';
        $html .= '<ul class="pagination justify-content-center">';
        
        if ($this->paginationInfo['hasPrevPage']) {
            $html .= $this->renderPageLink(
                $this->paginationInfo['prevPage'],
                '&laquo; Предыдущая',
                'page-link'
            );
        } else {
            $html .= '<li class="page-item disabled"><span class="page-link">&laquo; Предыдущая</span></li>';
        }
        
        $startPage = max(1, $this->paginationInfo['currentPage'] - 2);
        $endPage = min($this->paginationInfo['totalPages'], $this->paginationInfo['currentPage'] + 2);
        
        if ($startPage > 1) {
            $html .= $this->renderPageLink(1, '1', 'page-link');
            if ($startPage > 2) {
                $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
        }
        
        for ($i = $startPage; $i <= $endPage; $i++) {
            if ($i == $this->paginationInfo['currentPage']) {
                $html .= '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
            } else {
                $html .= $this->renderPageLink($i, (string)$i, 'page-link');
            }
        }
        
        if ($endPage < $this->paginationInfo['totalPages']) {
            if ($endPage < $this->paginationInfo['totalPages'] - 1) {
                $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
            $html .= $this->renderPageLink($this->paginationInfo['totalPages'], (string)$this->paginationInfo['totalPages'], 'page-link');
        }
        
        if ($this->paginationInfo['hasNextPage']) {
            $html .= $this->renderPageLink(
                $this->paginationInfo['nextPage'],
                'Следующая &raquo;',
                'page-link'
            );
        } else {
            $html .= '<li class="page-item disabled"><span class="page-link">Следующая &raquo;</span></li>';
        }
        
        $html .= '</ul>';
        $html .= '</nav>';
        
        return $html;
    }
    
    private function renderPageLink(int $page, string $text, string $class): string
    {
        $url = array_merge([$this->route], $this->params, ['page' => $page]);
        return '<li class="page-item">' . Html::a($text, $url, ['class' => $class]) . '</li>';
    }
}
