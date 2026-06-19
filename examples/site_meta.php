<?php
/**
 * 站点元信息管理模块
 * 使用数组保存站点元数据，并提供生成简短描述文本的方法
 */

class SiteMetaManager
{
    private $metaData;

    public function __construct()
    {
        $this->metaData = $this->getDefaultMeta();
    }

    private function getDefaultMeta()
    {
        return [
            'site_name' => '爱游戏官方平台',
            'domain' => 'https://main-official-aiyouxi.com.cn',
            'keywords' => ['爱游戏', '游戏资讯', '手游推荐', '电竞赛事'],
            'description' => '爱游戏官方平台提供最新游戏资讯、热门手游推荐和专业电竞赛事报道。',
            'author' => '爱游戏团队',
            'version' => '1.2.0',
            'language' => 'zh-CN',
            'charset' => 'UTF-8',
            'category' => '游戏门户',
            'year' => date('Y')
        ];
    }

    public function setMeta($key, $value)
    {
        if (array_key_exists($key, $this->metaData)) {
            $this->metaData[$key] = $value;
        }
    }

    public function getMeta($key)
    {
        return isset($this->metaData[$key]) ? $this->metaData[$key] : null;
    }

    public function updateKeywords(array $newKeywords)
    {
        $this->metaData['keywords'] = array_unique(
            array_merge($this->metaData['keywords'], $newKeywords)
        );
    }

    public function generateBriefDescription()
    {
        $name = htmlspecialchars($this->metaData['site_name'], ENT_QUOTES, 'UTF-8');
        $desc = htmlspecialchars($this->metaData['description'], ENT_QUOTES, 'UTF-8');
        $domain = htmlspecialchars($this->metaData['domain'], ENT_QUOTES, 'UTF-8');
        $keywords = implode('、', array_map(function($kw) {
            return htmlspecialchars($kw, ENT_QUOTES, 'UTF-8');
        }, $this->metaData['keywords']));

        return "{$name}（{$domain}）—— {$desc} 核心关键词：{$keywords}。";
    }

    public function getShortDescription($maxLength = 100)
    {
        $brief = $this->generateBriefDescription();
        if (mb_strlen($brief, 'UTF-8') > $maxLength) {
            $brief = mb_substr($brief, 0, $maxLength - 3, 'UTF-8') . '...';
        }
        return $brief;
    }

    public function toArray()
    {
        return $this->metaData;
    }

    public function displayMetaTable()
    {
        echo "<table border='1' cellpadding='5' style='border-collapse:collapse;'>\n";
        echo "<tr><th>属性</th><th>值</th></tr>\n";
        foreach ($this->metaData as $key => $value) {
            $safeKey = htmlspecialchars($key, ENT_QUOTES, 'UTF-8');
            if (is_array($value)) {
                $safeValue = htmlspecialchars(implode(', ', $value), ENT_QUOTES, 'UTF-8');
            } else {
                $safeValue = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            }
            echo "<tr><td>{$safeKey}</td><td>{$safeValue}</td></tr>\n";
        }
        echo "</table>\n";
    }
}

// 使用示例
$manager = new SiteMetaManager();
$manager->updateKeywords(['爱游戏', '新游评测']);
$manager->setMeta('author', '爱游戏编辑部');

echo "<h2>站点元信息</h2>\n";
$manager->displayMetaTable();

echo "<h2>简短描述</h2>\n";
echo "<p>" . $manager->getShortDescription(80) . "</p>\n";

echo "<h2>完整描述</h2>\n";
echo "<p>" . $manager->generateBriefDescription() . "</p>\n";