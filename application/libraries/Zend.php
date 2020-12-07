<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Zend {

    public function index_data($data)
    {
        require_once "ZendSearch/Lucene/LockManager.php";
        require_once "ZendSearch/Lucene/Index/TermsStreamInterface.php";
        require_once "ZendSearch/Lucene/Lucene.php";
        require_once 'ZendSearch/Lucene/SearchIndexInterface.php';
        require_once "ZendSearch/Lucene/Storage/Directory/DirectoryInterface.php";
        require_once "ZendSearch/Lucene/Storage/Directory/DirectoryInterface.php";
        require_once "ZendSearch/Lucene/Storage/Directory/Filesystem.php";
        require_once "ZendSearch/Lucene/Storage/File/FileInterface.php";
        require_once "ZendSearch/Lucene/Storage/File/AbstractFile.php";
        require_once "Stdlib/ErrorHandler.php";
      
        require_once "ZendSearch/Exception/ExceptionInterface.php";
        require_once "ZendSearch/Lucene/Exception/ExceptionInterface.php";
        require_once "ZendSearch/Lucene/Exception/InvalidArgumentException.php";
        require_once "ZendSearch/Lucene/Exception/RuntimeException.php";


        require_once "ZendSearch/Lucene/AbstractPriorityQueue.php";
        require_once "ZendSearch/Lucene/Storage/File/Filesystem.php";
         require_once "ZendSearch/Lucene/Index/TermsPriorityQueue.php";
        require_once "ZendSearch/Lucene/Index/TermsPriorityQueue.php";
        require_once "ZendSearch/Lucene/Index/SegmentWriter/AbstractSegmentWriter.php";
        require_once "ZendSearch/Lucene/Index/SegmentWriter/StreamWriter.php";
        require_once "ZendSearch/Lucene/Index/SegmentMerger.php";
        require_once "ZendSearch/Lucene/Index/FieldInfo.php";
        require_once "ZendSearch/Lucene/Index/TermInfo.php";
        require_once "ZendSearch/Lucene/Index/SegmentWriter/AbstractSegmentWriter.php";
        require_once "ZendSearch/Lucene/Analysis/Analyzer/AnalyzerInterface.php";
        require_once "ZendSearch/Lucene/Analysis/Analyzer/AbstractAnalyzer.php";
        require_once "ZendSearch/Lucene/Analysis/Analyzer/Common/AbstractCommon.php";
        require_once "ZendSearch/Lucene/Analysis/Analyzer/Common/Text.php";

        require_once "ZendSearch/Lucene/Analysis/Token.php";
        require_once "ZendSearch/Lucene/Analysis/TokenFilter/TokenFilterInterface.php";
        require_once "ZendSearch/Lucene/Analysis/TokenFilter/LowerCase.php";
        require_once "ZendSearch/Lucene/Analysis/Analyzer/Common/Text/CaseInsensitive.php";
        require_once "ZendSearch/Lucene/Analysis/Analyzer/Analyzer.php";


        require_once "ZendSearch/Lucene/Index/Term.php";
        require_once "ZendSearch/Lucene/Index/SegmentInfo.php";
        require_once "ZendSearch/Lucene/Index/SegmentWriter/DocumentWriter.php";
        require_once 'ZendSearch/Lucene/Search/Similarity/AbstractSimilarity.php';
        require_once 'ZendSearch/Lucene/Search/Similarity/DefaultSimilarity.php';
        
        require_once "ZendSearch/Lucene/Index/Writer.php";
        require_once "ZendSearch/Lucene/Index.php";
        require_once "ZendSearch/Lucene/Document.php";
        require_once "ZendSearch/Lucene/Document/Field.php";
        
        $CI =& get_instance();
        $user_id = $CI->session->userdata('user_id');
        $index = "";
        
        $path = "public/docindex/$user_id";
        if(!is_dir($path)){
            $index = ZendSearch\Lucene\Lucene::create($path);
        }
        else{
            $index = ZendSearch\Lucene\Lucene::open($path);
        }

        $doc = new ZendSearch\Lucene\Document();
        
        $docId = $data['id'];
        $doc->addField(ZendSearch\Lucene\Document\Field::unIndexed('identifiercontents', $docId));

        $docType = $data['type'];
        $doc->addField(ZendSearch\Lucene\Document\Field::unIndexed('type', $docType));

        // Index document contents
        $docContent = $data['contents'];
        $doc->addField(ZendSearch\Lucene\Document\Field::text('contents', $docContent));

        // Add document to the index
        $index->addDocument($doc);
        $index->commit();
    }
    
    
    public function search_data($query){
    require_once "/ZendSearch/Lucene/LockManager.php";
    require_once "/ZendSearch/Lucene/Index/TermsStreamInterface.php";
    require_once "/ZendSearch/Lucene/Lucene.php";
    require_once '/ZendSearch/Lucene/SearchIndexInterface.php';
    require_once "/ZendSearch/Lucene/Storage/Directory/DirectoryInterface.php";
    require_once "/ZendSearch/Lucene/Storage/File/FileInterface.php";
    require_once "/ZendSearch/Lucene/Storage/File/AbstractFile.php";
    require_once "/Stdlib/ErrorHandler.php";
 
    require_once "/ZendSearch/Exception/ExceptionInterface.php";
    require_once "/ZendSearch/Lucene/Exception/ExceptionInterface.php";
    require_once "/ZendSearch/Lucene/Exception/InvalidArgumentException.php";
    require_once "/ZendSearch/Lucene/Exception/RuntimeException.php";


    require_once "/ZendSearch/Lucene/Storage/Directory/Filesystem.php";
    require_once "/ZendSearch/Lucene/Storage/File/Filesystem.php";
    require_once "/ZendSearch/Lucene/Index/FieldInfo.php";
    require_once "/ZendSearch/Lucene/Index/SegmentWriter/AbstractSegmentWriter.php";
    require_once "/ZendSearch/Lucene/Analysis/Analyzer/AnalyzerInterface.php";
    require_once "/ZendSearch/Lucene/Analysis/Analyzer/AbstractAnalyzer.php";
    require_once "/ZendSearch/Lucene/Analysis/Analyzer/Common/AbstractCommon.php";
    require_once "/ZendSearch/Lucene/Analysis/Analyzer/Common/Text.php";

    require_once "/ZendSearch/Lucene/Analysis/Token.php";
    require_once "/ZendSearch/Lucene/Analysis/TokenFilter/TokenFilterInterface.php";
    require_once "/ZendSearch/Lucene/Analysis/TokenFilter/LowerCase.php";
    require_once "/ZendSearch/Lucene/Analysis/Analyzer/Common/Text/CaseInsensitive.php";
    require_once "/ZendSearch/Lucene/Analysis/Analyzer/Analyzer.php";


    require_once "/ZendSearch/Lucene/Index/Term.php";
    require_once "/ZendSearch/Lucene/Index/SegmentWriter/DocumentWriter.php";
    require_once '/ZendSearch/Lucene/Search/Similarity/AbstractSimilarity.php';
    require_once '/ZendSearch/Lucene/Search/Similarity/DefaultSimilarity.php';
    require_once "/ZendSearch/Lucene/Index/Writer.php";
    require_once '/ZendSearch/Lucene/AbstractFSM.php';
    require_once '/ZendSearch/Lucene/FSMAction.php';
    require_once '/ZendSearch/Lucene/Search/QueryLexer.php';
    require_once '/ZendSearch/Lucene/Search/QueryToken.php';
    require_once '/ZendSearch/Lucene/Search/QueryParserContext.php';
    require_once '/ZendSearch/Lucene/Search/QueryEntry/AbstractQueryEntry.php';
    require_once '/ZendSearch/Lucene/Search/Query/AbstractQuery.php';
    require_once '/ZendSearch/Lucene/Search/Query/Boolean.php';
    require_once '/ZendSearch/Lucene/Search/Query/Boolean.php';
    require_once '/ZendSearch/Lucene/Search/Query/Preprocessing/AbstractPreprocessing.php';
    require_once '/ZendSearch/Lucene/Search/Query/MultiTerm.php';
    require_once '/ZendSearch/Lucene/Search/Query/EmptyResult.php';
    
    require_once '/ZendSearch/Lucene/Search/QueryHit.php';
    require_once '/ZendSearch/Lucene/Search/Weight/AbstractWeight.php';
    require_once '/ZendSearch/Lucene/Search/Weight/Term.php';
    require_once '/ZendSearch/Lucene/Search/Query/Term.php';
    
    require_once '/ZendSearch/Lucene/Search/Query/Insignificant.php';
    require_once '/ZendSearch/Lucene/Search/Query/Preprocessing/Term.php';
    require_once '/ZendSearch/Lucene/Search/QueryEntry/Term.php';
    require_once '/ZendSearch/Lucene/Search/QueryParser.php';
    require_once "/ZendSearch/Lucene/Index/TermInfo.php";
    require_once "/ZendSearch/Lucene/Index/DictionaryLoader.php";
    require_once "/ZendSearch/Lucene/Index/SegmentInfo.php";
    require_once "/ZendSearch/Lucene/Index.php";
    require_once "/ZendSearch/Lucene/Document.php";
    require_once "/ZendSearch/Lucene/Document/Field.php";
    
    $CI =& get_instance();
        $user_id = $CI->session->userdata('user_id');
        $index = "";
        $path = "public/docindex/$user_id";
        
        $index = ZendSearch\Lucene\Lucene::open($path);
        $hits = $index->find($query);
        $data = array();
        foreach ($hits as $hit) {
            $data[] = array(
                'contents_id' => $hit->identifiercontents,
                'type' => $hit->type,
                'contents' => $hit->contents
            );
        }
        return $data;
    }
}
?>
